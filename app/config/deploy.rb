set :application, "Lunch"
set :domain,      "lunch.codingninja.com.au"
set :deploy_to,   "/var/www/lunch"
set :shared_files,      ["app/config/parameters.yml", "composer.phar", "vendor"]
set :shared_children,     ["vendor", "web/uploads"]
set :asset_children,    []
set :dump_assetic_assets, true
set :dump_assets, true
set :deploy_via,  :remote_cache
set :app_path,    "app"
set :use_composer, true
set :user, "codingninja"
set :use_sudo, false
set :cache_warmup, true
set :maintenance_basename, "maintenance"
set :repository,  "https://github.com/CodingNinja/lunch"
set :scm,         :git
role :app,        "54.251.59.94"

set :model_manager, "doctrine"

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
default_run_options[:pty] = true

after "deploy:create_symlink" do
    run "chmod -Rf 0777 #{deploy_to}/current/app/cache #{deploy_to}/current/app/logs"
end
