set :domain,      "188.226.163.161"
set :deploy_to,   "/var/www/php/best4car.evercodelab.com"
set :user,        "deployer"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

set :branch,      "development"

# after "deploy:update_code", "deploy:block_robots"
