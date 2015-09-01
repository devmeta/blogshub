
<h3>Import SQL</h3>

    zcat vendor/caldero/schema/schema.sql.gz | mysql -u root -p******** blogs

<h3>Configure DB Access</h3>

    nano vendor/caldero/config.php</code>


<h3>Nginx</h3>

    server {

      root /var/www/blogshub/public;

      index index.php index.html index.htm;

      server_name blogshub.domain.com;

      location / {
        try_files $uri $uri/ /index.php$is_args$args;
      }

      location ~ \.php$ {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
      }

      location ~ \.html$ {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
      }        
    }

<h3>Apache2</h3>

    <IfModule mod_rewrite.c>
      <IfModule mod_negotiation.c>
        Options -MultiViews
      </IfModule>

      RewriteEngine On

      # Redirect Trailing Slashes...
      RewriteRule ^(.*)/$ /$1 [L,R=301]

      # Handle Front Controller...
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteRule ^ index.php [L]
    </IfModule>
