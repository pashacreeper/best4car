set :domain,      "178.63.9.207"
set :deploy_to,   "/var/www/creeper/data/www/#{application}"
set :user,        "deployer"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

run "cp /var/www/php/#{application}/web/robots_prod.txt /var/www/php/#{application}/web/robots.txt"
