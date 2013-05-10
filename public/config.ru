# config.ru for Pow + Wordpress, based on http://stuff-things.net/2011/05/16/legacy-development-with-pow/
# added hackery to work around wordpress issues - Patrick Anderson (patrick@trinity-ai.com)
# added rubygems, replaced script_path with script from path_parts, added to_return to fix return error - Paul Cook
# clearly this could be cleaner, but it does work
# added rewrite rule for WordPress Multi Site - Per Soderlind (see also http://soderlind.no/archives/2012/12/02/wordpress-and-pow/)

require 'rubygems'
require 'rack'
require 'rack-legacy'
require 'rack-rewrite'

# patch Php from rack-legacy to substitute the original request so 
# WP's redirect_canonical doesn't do an infinite redirect of /
module Rack
  module Legacy
    class Php
      def run(env, path)
        config = {'cgi.force_redirect' => 0}
        config.merge! HtAccess.merge_all(path, public_dir) if @htaccess_enabled
        config = config.collect {|(key, value)| "#{key}=#{value}"}
        config.collect! {|kv| ['-d', kv]}

	script, info = *path_parts(path)
        env['SCRIPT_FILENAME'] = script
        env['SCRIPT_NAME'] = script.sub ::File.expand_path(public_dir), ''
        env['REQUEST_URI'] = env['POW_ORIGINAL_REQUEST'] unless env['POW_ORIGINAL_REQUEST'].nil?

        super env, @php_exe, *config.flatten
      end
    end
  end
end

INDEXES = ['index.html','index.php', 'index.cgi']

use Rack::Rewrite do
  # Rewrite rule for WordPress Multi Site
  rewrite %r{.*/files/(.+)}, 'wp-includes/ms-files.php?file=$1'

  # redirect /foo to /foo/ - emulate the canonical WP .htaccess rewrites
  r301 %r{(^.*/[\w\-_]+$)}, '$1/'

  rewrite %r{(.*/$)}, lambda {|match, rack_env|
    rack_env['POW_ORIGINAL_REQUEST'] = rack_env['PATH_INFO']

    if !File.exists?(File.join(Dir.getwd, rack_env['PATH_INFO']))
      return '/index.php'
    end
    
    to_return = rack_env['PATH_INFO']
    INDEXES.each do |index|
      if File.exists?(File.join(Dir.getwd, rack_env['PATH_INFO'], index))
        to_return = File.join(rack_env['PATH_INFO'], index)
      end
    end
    to_return
  }

  # also rewrite /?p=1 type requests
  rewrite %r{(.*/\?.*$)}, lambda {|match, rack_env|
    rack_env['POW_ORIGINAL_REQUEST'] = rack_env['PATH_INFO']
    query = match[1].split('?').last
		
    if !File.exists?(File.join(Dir.getwd, rack_env['PATH_INFO']))
      return '/index.php?' + query 
    end
    
    to_return = rack_env['PATH_INFO'] + '?' + query
    INDEXES.each do |index|
      if File.exists?(File.join(Dir.getwd, rack_env['PATH_INFO'], index))
        to_return = File.join(rack_env['PATH_INFO'], index) + '?' + query
      end
    end
    to_return
  }
end

use Rack::Legacy::Php, Dir.getwd
use Rack::Legacy::Cgi, Dir.getwd
run Rack::File.new Dir.getwd