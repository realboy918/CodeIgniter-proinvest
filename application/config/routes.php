<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = "home";
$route['404_override'] = 'home/error_404';
$route['translate_uri_dashes'] = FALSE;

/*********** WEBSITE ROUTES ************************/
$route['faqs'] = "home/faqs";
$route['terms'] = "home/terms";
$route['privacy'] = "home/privacy";
$route['contactus'] = "home/contact_us";

/************* AUTH ROUTES *************/
$route['signup'] = 'auth/signup';
$route['signup/(:any)'] = 'auth/signup/$1';
$route['login_auth'] = 'auth/loginMe';
$route['login'] = 'auth/loginView';
$route['confirmpass'] = 'auth/checkPass';
$route['forgotPassword'] = "auth/forgotPassword";
$route['resetPasswordUser'] = "auth/resetPasswordUser";
$route['resetPassword'] = "auth/resetPasswordConfirmUser";
$route['resetPassword/(:any)'] = "auth/resetPasswordConfirmUser/$1";
$route['resetPassword/(:any)/(:any)'] = "auth/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "auth/createPasswordUser";
$route['changepass'] = "user/changePassword";

/*********** SETTINGS ROUTES *******************/
$route['settings'] = 'settings/settings';
$route['settings/companyInfo'] = 'settings/companyInfoUpdate';
$route['settings/emailInfo'] = 'settings/emailInfoUpdate';
$route['settings/email_templates'] = 'settings/email_templates';
$route['settings/edit_email'] = 'settings/editEmailTemplate';
$route['emailTemplate'] = "Settings/email_template"; 
$route['paymentAPIInfo'] = "settings/addons_info";
$route['paymentmethodInfo'] = "settings/paymentmethodInfo";
$route['settings/referral'] = "Settings/referralEdit"; 
$route['settings/addonAPIUpdate'] = "settings/addons_update";
$route['settings/paymentMethodUpdate'] = "settings/paymentMethodEdit";
$route['settings/seo'] = "Settings/SEO_Update"; 
$route['addpaymentmethod'] = "Settings/addpaymentmethod";
$route['deletepaymentmethod/(:any)'] = "settings/deletepaymentmethod/$1";

/*********** LANGUAGES ROUTES *******************/
$route['change_language/(:num)'] = "Languages/change_language/$1"; 
$route['settings/languages'] = "Languages/languages"; 
$route['settings/getLangSettings/(:any)/(:any)'] = "Languages/getLangSettings/$1/$2"; 
$route['settings/addLanguage'] = "Languages/addLanguage"; 
$route['settings/editLanguage'] = "Languages/editLanguage"; 
$route['settings/getLang/(:any)'] = "Languages/getLang/$1"; 
$route['settings/editTranslation'] = "Languages/editTranslation";
$route['switchlang/(:any)'] = 'home/switchLang/$1';

/*********** USER DEFINED ROUTES *******************/
$route['dashboard'] = 'user';
$route['invite'] = 'referrals/invite';
$route['logout'] = 'user/logout';

$route['addNew'] = "user/addNew";
$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser/(:any)'] = "user/deleteUser/$1";
$route['profile'] = "user/profile";
$route['profile/(:any)'] = "user/profile/$1";
$route['profileUpdate'] = "user/profileUpdate";
$route['profileUpdate/(:any)'] = "user/profileUpdate/$1";
$route['paymentInfo'] = "user/paymentAccountUpdate";
$route['activate2fa'] = "user/activate_twfa";
$route['user/logo_update'] = "user/logo_update";

$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['changePassword/(:any)'] = "user/changePassword/$1";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";
$route['login-history'] = "user/loginHistoy";
$route['login-history/(:num)'] = "user/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "user/loginHistoy/$1/$2";
$route['referrals'] = 'referrals/referrals';


/*********** TEAM ROUTES *******************/
$route['team'] = 'user/team';
$route['team/(:num)'] = "user/team/$1";
$route['team/newManager'] = "user/addNewManager";
$route['team/editManager/(:num)'] = "user/editManager/$1";

/*********** CLIENTS ROUTES *******************/
$route['clients'] = 'user/clients';
$route['clients/newClient'] = "user/addNewClient";
$route['clients/viewClient/(:num)'] = "user/viewClient/$1";
$route['clients/(:num)'] = "user/clients/$1";
$route['clients/editClient/(:num)'] = "user/editClient/$1";

/*********** PLANS ROUTES *******************/
$route['plans'] = 'plans/inPlans';
$route['plans/(:num)'] = "plans/inPlans/$1";
$route['plans/new'] = "plans/addNewPlan";
$route['plans/edit/(:num)'] = "plans/editPlan/$1";
$route['plans/delete/(:num)'] = "plans/deletePlan/$1";

/*********** DEPOSITS ROUTES *******************/
$route['deposits'] = 'transactions/deposits';
$route['deposits/(:num)'] = "transactions/deposits/$1";
$route['deposits/new'] = "transactions/newDeposit";
$route['deposits/payment'] = "transactions/paymentPage";
$route['bitcoinPayment'] = "transactions/bitcoinDeposit";
$route['deposits/editTrans/(:num)'] = "transactions/editDeposit/$1";
$route['deposits/cancelTrans/(:num)'] = "transactions/cancelDeposit/$1";
$route['deposits/deleteTrans/(:num)'] = "transactions/deleteDeposit/$1";


/*********** WITHDRAWAL ROUTES *******************/
$route['withdrawals'] = 'transactions/withdrawals';
$route['withdrawals/(:num)'] = 'transactions/withdrawals/$1';
$route['withdrawals/new'] = "transactions/newWithdrawal";
$route["withdrawDeposit"] = "transactions/withdrawDeposit";
$route["withdraw"] = "transactions/withdraw";
$route["reinvest"] = "transactions/reInvest";
$route['approveWithdrawal/(:num)'] = "transactions/approveWithdrawal/$1";
$route['declineWithdrawal/(:num)'] = "transactions/declineWithdrawal/$1";
$route['withdrawalInfo/(:any)/(:any)/(:any)'] = 'transactions/withdrawalInfo/$1/$2/$3';
$route['user_payment_accounts/(:num)'] = "user/user_payment_accounts/$1";

/*********** PAYMENTS ROUTES *******************/
$route['earnings'] = 'transactions/earnings';
$route['earnings/(:num)'] = 'transactions/earnings/$1';


/*********** COINPAYMENTS ROUTES *******************/
$route['coin-payment'] = "Coinpayments";
$route['ipncp/(:any)'] = "Coinpayments/IPN_Response/$1";
$route['checkpayment/(:any)'] = "Coinpayments/checkCoinPayments/$1";

/*********** MANUAL TRANSFER ROUTES *******************/
$route['manual-payment'] = "transactions/manualTransfer";
$route['add_manual_transfer'] = "transactions/add_manual_transfer";

/*********** BANK TRANSFER ROUTES *******************/
$route['bank-transfer'] = "transactions/bankTransfer";
$route['add_bank_transfer'] = "transactions/add_bank_transfer";

/*********** PAYPAL ROUTES *******************/
$route['paypal-payment'] = "Paypal/index";
$route['paypal/callback'] = "Paypal/callback";
$route['paypal/success'] = "Paypal/success";
$route['paypal/cancelled'] = "Paypal/canceled";

/*********** STRIPE ROUTES *******************/
$route['stripe-payment'] = "Stripe";
$route['stripePost']['post'] = "Stripe/stripePost";
$route['stripepaymentsuccess'] = "Stripe/success";

/*********** PAYEER ROUTES *******************/
$route['payeer/success'] = "Payeer/success";
$route['payeer/cancelled'] = "Payeer/canceled";
$route['payeer/ipn'] = "Payeer/IPN_Response";

/*********** PAYSTACK ROUTES *******************/
$route['paystack'] = "Paystack/paystack_standard";
$route['paystack_callback'] = "Paystack/verify_payment";
$route['paystack_status'] = "Paystack/webhook";
$route['paystack/success/(:any)'] = "Paystack/success/$1";
$route['paystack/fail'] = "Paystack/fail";

/********** TICKETING ROUTES *********************/
$route['create_ticket'] = "Tickets/createTicket";
$route['bulk_assign_ticket'] = "Tickets/bulkAssign";
$route['bulk_prioritise_ticket'] = "Tickets/bulkPriority";
$route['bulk_resolve_ticket'] = "Tickets/bulkResolve";
$route['assign_ticket/(:num)/(:num)'] = "Tickets/assignTicket/$1/$2";
$route['assign_priority/(:num)'] = "Tickets/ticketPriority/$1";
$route['tickets'] = "Tickets/listTickets";
$route['tickets/(:any)'] = "Tickets/listTickets/$1";
$route['tickets/(:any)'] = "Tickets/listTickets/$1";
$route['ticket/(:any)'] = "Tickets/viewTicket/$1";
$route['ticket_categories'] = "Tickets/ticketCategories";
$route['remove_ticket_filter'] = "Tickets/remove_filter";
$route['priority_filter'] = "Tickets/priority_filter";
$route['team_filter'] = "Tickets/team_filter";
$route['ticket/comment/(:num)'] = "Tickets/comment/$1";
$route['previous_messages/(:num)'] = "Tickets/viewPreviousMessages/$1";
$route['ticket/closed/(:num)'] = "Tickets/resolve/$1";
$route['ticket/opened/(:num)'] = "Tickets/reopen/$1";

/********** VERIFICATION ROUTES *********************/
$route['verification'] = "verification/verify";
$route['verification/(:any)'] = 'verification/verify/$1';
$route['verificationupload'] = 'verification/submit';
$route['verification/approval/(:any)'] = 'verification/approve/$1';
$route['ver_reject/(:any)'] = 'verification/reject_approval/$1';
$route['ver_approve/(:any)'] = 'verification/accept_approval/$1';


/*********** WEB CONTROL ROUTES *******************/
$route['webcontrol/templates'] = "webcontrol/templates";
$route['webcontrol/templates/(:num)'] = "webcontrol/templates/$1";
$route['webcontrol/faq'] = "webcontrol/FAQs";
$route['webcontrol/faq/(:num)'] = "webcontrol/FAQs/$1";
$route['create_faq'] = "webcontrol/createFaq";
$route['edit_faq/(:any)'] = "webcontrol/editFaq/$1";
$route['delete_faq/(:any)'] = "webcontrol/deleteFaq/$1";
$route['webcontrol/terms'] = "webcontrol/terms";
$route['edit_terms'] = "webcontrol/editTerms";
$route['webcontrol/policy'] = "webcontrol/policy";
$route['edit_policy'] = "webcontrol/editPolicy";
$route['webcontrol/footer'] = "webcontrol/footer";
$route['edit_footer'] = "webcontrol/editFooter";
$route['webcontrol/builder/(:num)'] = "webcontrol/templateBuilder/$1";
$route['webcontrol/builder/save'] = "webcontrol/editBuilder";

/********** CRON JOBS ROUTES *********************/
$route['emailcronjob'] = 'home/earningsEmails';
/* End of file routes.php */
/* Location: ./application/config/routes.php */
