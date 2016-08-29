
# Set the Environment Variable
#environment = :development
environment = :production

# Location of resources.
css_dir = "css"
sass_dir = "css/sass"
images_dir = "css/images"

#output_style = :expanded or :nested or :compact or :compressed
output_style = (environment == :development) ? :expanded : :compressed

# Enable relative paths to assets 
relative_assets = true

# debugging comments 
line_comments = false

# Pass options to sass.
# - For development, we turn on the FireSass-compatible debug_info.
# - For production, we force the CSS to be regenerated even though the source
#   scss may not have changed, since we want the CSS to be compressed and have
#   the debug info removed.
sass_options = (environment == :development) ? {:debug_info => true} : {:always_update => true}
sass_options = {:sourcemap => true}
