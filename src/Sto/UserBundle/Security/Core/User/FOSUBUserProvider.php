<?php
namespace Sto\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use FOS\UserBundle\Model\UserManagerInterface;
use Doctrine\ORM\EntityManager;

class FOSUBUserProvider extends BaseClass
{
    protected $em;

    protected $mailServer;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager FOSUB user provider.
     * @param EntityManager        $em
     * @param array                $properties  Property mapping.
     */
    public function __construct(UserManagerInterface $userManager, EntityManager $em, array $properties, $mail_server)
    {
        $this->userManager = $userManager;
        $this->em = $em;
        $this->properties  = $properties;
        $this->mailServer = $mail_server;
    }

    /**
     * {@inheritDoc}
     */
    public function connect($user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();

        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $url = 'https://api.vk.com/method/users.get?fields=sex,city,nickname,photo_max_orig&access_token='.$response->getAccessToken();
            $additional_data=json_decode(file_get_contents($url));
            $photo = $additional_data->response[0]->photo_max_orig;
            $city_id = $additional_data->response[0]->city;
            $city=file_get_contents('https://api.vk.com/method/places.getCityById?cid='.$city_id);

            $nickname = $additional_data->response[0]->nickname;
            if ($nickname != '') {
                $user->setUsername($nickname);
            } else {
                $user->setUsername($username);
            }
            $user->setAvatarVk($photo);
            $user->setEmail($username.'@'.$this->mailServer);

            $user->setPassword($username);
            $user->setEnabled(true);
            $user->setUsingEmail(false);

            $user_data = $response->getResponse();

            $user_name = explode(' ', $user_data['response']['user_name']);
            $user->setFirstName($user_name[0]);
            $user->setLastName($user_name[1]);
            $user->setGender(($user_data['response']['user_name'] == 1) ? 'female' : 'male');

            $ratingGroup = $this->em->getRepository('StoUserBundle:RatingGroup')->find(1);
            $user->setRatingGroup($ratingGroup);
            $oCity = $this->em->getRepository('StoCoreBundle:Country')->findOneById(102);

            if ($oCity) {
                $user->setCity($oCity);
            }

            $this->userManager->updateUser($user);

            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }
}
