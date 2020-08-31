# Proinvest - HYIP, Cryptocurrency, Forex Investment Platform with MLM Support
**Admin Panel - User Management Demo using CodeIgniter + AdminLTE Bootstrap Theme**

This system is intended for use in creating and maintaining an investment platform that is secure for both the
site owner and client. 

## Features
1. A clean and modern user interface.
2. Configure and add your investment plans easily.
3. User verification system.
4. Role management (admin, managers).
5. Multiple payment gateways (Paypal, stripe and Coinpayments).
6. Referral system with multi-level management system.
7. Automated earnings based on the plan's period.
8. Manually review payouts and approve payments.
9. Deposit, withdrawals and earnings transaction list for both admin and user.
10. Allow re-investment or withdrawal of deposits upon expiry by users. 
11. Email templates
12. Configure and manage your payment API's from the settings page.
13. Support modern browser and cross browser compatibility.
14. Regular updates
15. Free premium and quick support 24/7


## Installation

**C-Panel**
- Login to your cPanel account or access your account via FTP
- Go to the public_html folder and upload the zipped folder (if using FTP, unzip the folder in your computer and move all files in the unzipped folder to public_html)
- Ensure that folder structure in public_html is (application folder, system folder, assets folder, index.php, .HTACCESS etc.) 
- Import your proinvest.sql in the unzipped folder to the live database using phpmyadmin tool from the cpanel.
- Once you do this, you will need to change the database configuration details, base URL & any other dependencies in application>config>database.php to look like this:

'hostname' => 'localhost',
'username' => '<Your phpmyadmin username>',
'password' => '<your phpmyadmin password?'

- Afterwards go to your main url and the site should be up and runnning.

**System Administrator Account :**

email : admin@proinvest.com

password : 12345678
