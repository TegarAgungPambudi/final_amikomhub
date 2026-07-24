# Deploy to alwaysdata

This file describes the minimal steps to deploy the `final_amikomhub` Laravel application to alwaysdata.

## 1. Prepare SSH access
- Add your public SSH key to alwaysdata (Account → SSH keys).

## 2. Clone repository on alwaysdata
```bash
# on alwaysdata shell
git clone git@github.com:TegarAgungPambudi/final_amikomhub.git ~/final_amikomhub
cd ~/final_amikomhub
git checkout FP_Amikom_Hub
```

## 3. Install dependencies
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env for production (DB, MAIL, MIDTRANS, etc.)
php artisan key:generate
```

## 4. Database & storage
```bash
php artisan migrate --force
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

## 5. Web root
In the alwaysdata panel, set the web directory (document root) to:
```
/home/<youruser>/final_amikomhub/public
```

## 6. Scheduler
Add a cron job in alwaysdata to run Laravel scheduler every minute:
```
* * * * * php /home/<youruser>/final_amikomhub/artisan schedule:run >> /dev/null 2>&1
```

## 7. Optional: queue workers
- Use alwaysdata's process manager or supervisor equivalent to run `php artisan queue:work` if you use queues.

## 8. HTTPS and domain
- Configure your domain in alwaysdata and enable Let's Encrypt SSL from the panel.

## 9. Notes
- Keep secrets out of the repo. Use the alwaysdata environment panel or `.env` on the server.
- If you prefer HTTPS git pushes, make sure your account can authenticate with a PAT when pushing from local.

If you want, I can also create a `.env.production` template (without secrets) and add deployment scripts.
