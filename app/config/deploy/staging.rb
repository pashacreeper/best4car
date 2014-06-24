set :domain,      "188.226.163.161"
set :deploy_to,   "/var/www/php/best4car.evercodelab.com"
set :user,        "deployer"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

set :branch,      "development"

namespace :symfony do
  desc "Clear apc cache"
  task :clear_apc do
    capifony_pretty_print "--> Clear apc cache"
    run "#{try_sudo} sh -c 'cd #{current_path} && #{php_bin} #{symfony_console} apc:clear --env=#{symfony_env_prod}'"
    capifony_puts_ok
  end
end

after "deploy:create_symlink", "symfony:clear_apc"

# after "deploy:update_code", "deploy:block_robots"
