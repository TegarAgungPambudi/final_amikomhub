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

## 10. Optional: Automatic deploy from GitHub

You can configure a GitHub Actions workflow that deploys to alwaysdata whenever you push to the `FP_Amikom_Hub` branch. Steps:

1. Add your public SSH key to alwaysdata (Account → SSH keys).
2. In this repository on GitHub, open `Settings → Secrets and variables → Actions` and add the following secrets:
	- `ALWAYSDATA_HOST`: your server host (example: ssh.alwaysdata.com or the host shown in your panel)
	- `ALWAYSDATA_USER`: your alwaysdata username
	- `ALWAYSDATA_SSH_KEY`: the private SSH key (PEM/openssh) corresponding to the public key you added to alwaysdata
	- `ALWAYSDATA_PORT` (optional, default 22)

3. The repository already includes a workflow at `.github/workflows/deploy.yml` which will run on push to `FP_Amikom_Hub` and execute the deploy script remotely.

Notes:
- Ensure the server has `git`, `php`, and `composer` installed and available in PATH.
- The workflow uses SSH deploy; keep the private key secret and never commit it.
- You can also run `scripts/deploy.sh` manually on the server.

