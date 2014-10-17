set :domain,      "178.63.9.207"
set :deploy_to,   "/var/www/creeper/data/www/staging.best4car"
set :user,        "deployer"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

set :branch,      "development"

# after "deploy:update_code", "deploy:block_robots"
