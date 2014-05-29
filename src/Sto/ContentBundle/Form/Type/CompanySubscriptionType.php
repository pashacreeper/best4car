<?php

namespace Sto\ContentBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\Extension\ChoiceList\SubscriptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanySubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ids = [];
        if (isset($options['subscriptions'])) {
            foreach ($options['subscriptions'] as $subscription) {
                $ids[] = $subscription->getMark()->getId();
            }
        }

        $builder
            ->add('type', 'hidden', [
                'data'      => SubscriptionType::COMPANY,
                'read_only' => true
            ])
            ->add('mark', 'entity', [
                'class' => 'Sto\CoreBundle\Entity\Mark',
                'query_builder' => function (EntityRepository $er) use ($ids) {
                    $qb = $er->createQueryBuilder('mark')->select('mark');
                    if (!empty($ids)) {
                        return $qb->where('mark.id NOT IN (:id)')
                            ->setParameter('id', $ids);
                    }

                    return $qb;
                }
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'subscriptions' => null
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return '';
    }
}
