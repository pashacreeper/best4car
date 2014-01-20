set :domain,      "78.46.216.120"
set :deploy_to,   "/var/www/php/#{application}"
set :user,        "dev-user"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

set :branch,      "features"

after "deploy:update_code", "deploy:block_robots"
