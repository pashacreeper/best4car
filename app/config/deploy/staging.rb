set :domain,      "78.46.216.120"
set :deploy_to,   "/var/www/php/#{application}"
set :user,        "dev-user"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

run "cp /var/www/php/#{application}/web/robots_staging.txt /var/www/php/#{application}/web/robots.txt"
