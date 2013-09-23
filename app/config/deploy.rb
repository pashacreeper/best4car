set :stage_dir, 'app/config/deploy'
require 'capistrano/ext/multistage'

set :stages, %w(prod staging)
set :default_stage, "staging"

# Capifony documentation: http://capifony.org
# Capistrano documentation: https://github.com/capistrano/capistrano/wiki

logger.level = Logger::MAX_LEVEL

set :application, "sto-expert"

set :scm,         :git
set :repository,  "git@github.com:pashacreeper/sto-expert.git"
set :branch,      "master"
set :deploy_via,  :remote_cache

ssh_options[:forward_agent] = true
default_run_options[:pty] = true

set :use_composer,   true
set :composer_options,  "--verbose --prefer-dist --optimize-autoloader"
set :update_vendors, false
set :dump_assetic_assets, true

set :writable_dirs,     [app_path + "/cache", app_path + "/logs"]
set :webserver_user,    "www-data"
set :permission_method, :acl
set :use_set_permissions, false
set :shared_files,    [app_path + "/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/storage", "vendor"]

set :model_manager, "doctrine"
set :interactive_mode, false
set :symfony_env_prod, "prod"

set :use_sudo,    false

set :keep_releases, 3
after "deploy", "deploy:cleanup"

require 'hipchat/capistrano'
set :hipchat_token, "915640c1f2d58ebf5d79d8ce4c1cd6"
set :hipchat_room_name, "Github"
set :hipchat_announce, false # notify users
set :hipchat_color, 'purple' # finished deployment message color
set :hipchat_failed_color, 'red' # cancelled deployment message color

# cron job
require "whenever/capistrano"
set :whenever_variables, ""
set :whenever_command, "whenever --load-file app/config/schedule.rb --set environment=#{symfony_env_prod}"