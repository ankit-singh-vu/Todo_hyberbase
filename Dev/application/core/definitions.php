<?php
/**
 * Definations
 *
 * PHP version 5.5.9
 *
 * Some low level defination about the the deployment of the framework.
 *
 * @category   Platform
 * @package    Functions
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  2014 - 2015, HB
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 *
 * @link       https://framework.local
 */

define('HYPERBASE_VERSION', 4.23);

/**
 * Defining Permission Level Types
 */
define('APP_LEVEL', 1);         //Application Level Permission
define('USR_LEVEL', 2);         //User Level Permission
define('DEFAULT_PERMISSION_LEVEL', APP_LEVEL);

/**
 * Define the types of deployment
 */
define('SAAS', 3);                  //SaaS Platform with support for multiple application with multiple instances
define('CONTAINER', 4);             //SaaS Platform with support for one application with multiple instances
define('APPLICATION', 5);           //SaaS Platform with support for one application with one instances
define('DEPLOYMENT_MODE', APPLICATION);

define('ADMIN_DOMAIN', 'ops.webngin.com');
define('REGISTRATION_DOMAIN', 'account.webngin.com');

define('FORMAT_HTML', 'html');
define('FORMAT_JSON', 'json');
define('FORMAT_JS', 'js');

define('FORCE_HTTPS_ACCESS', true);

define('HTTP_METHOD_ALL', 0);
define('HTTP_METHOD_GET', 1);
define('HTTP_METHOD_POST', 2);
define('HTTP_METHOD_PUT', 3);
define('HTTP_METHOD_DELETE', 4);

define('ACCESS_TYPE_WEB', 6);
define('ACCESS_TYPE_API', 7);
define('ACCESS_TYPE_CLI', 8);
define('ACCESS_TYPE_AGENT', 9);

define('STATUS_BLOCKED', 8);
define('STATUS_SUSPENDED', 9);
define('STATUS_ACTIVE', 10);
define('STATUS_UNMOUNTED', 12);
define('STATUS_DELETED', 11);

define('USER_STATUS_EMAIL_VERIFICATION_REQUIRED', 11);
define('TENANT_STATUS_NO_PLAN_SELECTED', 12);

define('PLAN_RECURRING_INTERVAL_MONTHLY', 15);
define('PLAN_RECURRING_INTERVAL_ANNUAL', 16);

define('SUBSCRIPTION_TRIAL', 17);
define('SUBSCRIPTION_PAYMENT_FAILED', 18);
define('SUBSCRIPTION_PAYMENT_METHOD_NOT_PRESENT', 19);
define('SUBSCRIPTION_DEMO', 20);
define('SUBSCRIPTION_NOT_ATTACHED_TO_PG', 21);

define('CAN_RECEIVE_SUPPORT_TICKETS', 22);
define('CAN_NOT_RECEIVE_SUPPORT_TICKETS', 23);



define('FORM_VALIDATE_TYPE_STRING', 1);

define('FORM_INPUT_DIMENSION_TYPE_FULL', 1);
define('FORM_INPUT_DIMENSION_TYPE_HALF', 2);
define('FORM_INPUT_DIMENSION_TYPE_ONE_THIRD', 3);

define('CLUSTER_TYPE_STANDALONE_WEB_SERVER', 0);
define('CLUSTER_TYPE_HA_STANDALONE_WEB_SERVER', 1);
define('CLUSTER_TYPE_MULTIPLE_WEB_SERVERS', 2);
define('CLUSTER_TYPE_HA_MULTIPLE_WEB_SERVERS', 3);
define('CLUSTER_TYPE_HA_SITE_MULTIPLE_WEB_SERVERS', 4);

define('APPLICATION_STATUS_ACTIVE', 1);
define('APPLICATION_STATUS_SUSPENDED', 2);

# SERVER TYPE DEFINITION
define('SERVER_TYPE_STANDALONE', 1);
define('SERVER_TYPE_ANODE', 1);
define('SERVER_TYPE_APPLIANCE', 2);
define('SERVER_TYPE_CLUSTER', 3);

define('SERVER_PLATFORM_SSH_INACTIVE', 0);
define('SERVER_PLATFORM_SSH_AUTH_TYPE_PASSWORD', 1);
define('SERVER_PLATFORM_SSH_AUTH_TYPE_KEY', 2);

define('SERVER_STATUS_PROVISIONING', 0);
define('SERVER_STATUS_RUNNING', 1);
define('SERVER_STATUS_SUSPENDED', 2);
define('SERVER_STATUS_DELETED', 3);

define('CLUSTER_STATUS_PROVISIONING', 0);
define('CLUSTER_STATUS_RUNNING', 1);
define('CLUSTER_STATUS_SUSPENDED', 2);
define('CLUSTER_STATUS_DELETED', 3);

define('DRIVE_TYPE_LOCAL_UNKNOWN', 0);
define('DRIVE_TYPE_LOCAL_HDD', 1);
define('DRIVE_TYPE_LOCAL_SSD', 2);
define('DRIVE_TYPE_NETWORK_HDD', 3);
define('DRIVE_TYPE_GLUSTER_FS', 4);

define('DRIVE_FORMAT_TYPE_EXT3', 1);
define('DRIVE_FORMAT_TYPE_EXT4', 1);

define('JOB_EXEC_TYPE_RUN', 1);
define('JOB_EXEC_TYPE_ROLLBACK', 2);

define('STORAGE_DEVICE_TYPE_ROOT', 1);
define('STORAGE_DEVICE_TYPE_DATA', 2);

define('SERVICE_STATE_CREATED', 0);
define('SERVICE_STATE_DEPLOYING', 1);
define('SERVICE_STATE_RUNNING', 2);
define('SERVICE_STATE_STOPPED', 3);
define('SERVICE_STATE_DELETED', 3);

define('INVOICE_PENDING', 0);
define('INVOICE_ACTIVE', 10);
define('INVOICE_PAID', 1);
define('INVOICE_CANCELLED', 2);

define('DOC_SENT', 0);
define('DOC_SIGNED', 1);
define('DOC_EXPIRED', 2);

define('COURSE_ACCESS_PRESENT', 0);
define('COURSE_ACCESS_BLOCKED', 1);

define('COURSE_NOT_STARTED', 0);
define('COURSE_ONGOING', 1);
define('COURSE_COMPLETED', 2);

define('WELCOME_CALL', 10);
define('ONE_TO_ONE_CALL', 11);
define('WEEKLY_CALL', 12);

define('TICKET_PENDING', 0);
define('TICKET_CLOSED', 1);

define('PAYMENT_LINK_CREATED', 0);
define('PAYMENT_LINK_SEND', 1);
define('PAYMENT_LINK_USED', 2);
define('PAYMENT_LINK_PAID', 3);



// defination upto 500
//