<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Transactions_model (Transactions Model)
 * User model class to get to handle user related data 
 * @author : Axis96
 * @version : 1.1
 * @since : 07 December 2019
 */
class Transactions_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function depositsListingCount($searchText = '', $userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.txnCode, BaseTbl.userId, firstName, lastName, BaseTbl.amount, BaseTbl.paymentMethod, BaseTbl.planId, BaseTbl.maturityDtm, BaseTbl.createdBy, BaseTbl.createdDtm');
        $this->db->from('tbl_deposits  as BaseTbl');
        $this->db->join('tbl_plans as Plan', 'Plan.id = BaseTbl.planId','left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get user deposits
     * The Following status codes apply
     * 0 - Transaction has been initiated, paid by the client and earnings generated
     * 1 - Deposit has been withdrawn by the client
     * 2 - Deposit has been reinvested by the client
     * 3 - Nature of the plan does not allow withdrawal or reinvestment of the deposit //Principal Return checkbox empty
     * 4 - The deposit has not been confirmed by admin (pending approval)
     * 5 - Not approved by admin
     */
    function deposits($searchText = '', $page, $segment, $userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.txnCode, BaseTbl.status, name, BaseTbl.userId, BaseTbl.amount, BaseTbl.paymentMethod, BaseTbl.planId, BaseTbl.maturityDtm, BaseTbl.createdBy, BaseTbl.createdDtm');
        $this->db->from('tbl_deposits  as BaseTbl');
        $this->db->join('tbl_plans as Plan', 'Plan.id = BaseTbl.planId','left');
        $this->db->where('userId =', $userId);
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('amount', $this->db->escape_like_str($searchText));
            $this->db->or_like('txnCode', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $this->db->order_by('createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function allDeposits($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.txnCode, BaseTbl.status, Plan.name, BaseTbl.userId, User.firstName, User.lastName, BaseTbl.amount, BaseTbl.paymentMethod, BaseTbl.planId, BaseTbl.maturityDtm, BaseTbl.createdBy, BaseTbl.createdDtm');
        $this->db->from('tbl_deposits  as BaseTbl');
        $this->db->join('tbl_plans as Plan', 'Plan.id = BaseTbl.planId','left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('Plan.name', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->order_by('createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function allDepositsListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.txnCode, BaseTbl.userId, User.firstName, User.lastName, BaseTbl.amount, BaseTbl.paymentMethod, BaseTbl.planId, BaseTbl.maturityDtm, BaseTbl.createdBy, BaseTbl.createdDtm');
        $this->db->from('tbl_deposits  as BaseTbl');
        $this->db->join('tbl_plans as Plan', 'Plan.id = BaseTbl.planId','left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('Plan.name', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get all earnings based on userId
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function earnings($searchText = '', $page, $segment, $userId, $date)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.type, BaseTbl.depositId, Deposit.userId, BaseTbl.txnCode, BaseTbl.amount, BaseTbl.createdDtm');
        $this->db->from('tbl_earnings  as BaseTbl');
        $this->db->join('tbl_deposits as Deposit', 'Deposit.id = BaseTbl.depositId','left');
        $this->db->where('BaseTbl.userId =', $userId);
        $this->db->where('BaseTbl.createdDtm <=', $date);
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $this->db->order_by('createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function earningsListingCount($searchText = '', $userId, $date)
    {
        $this->db->select('BaseTbl.id, BaseTbl.type, BaseTbl.depositId, Deposit.userId, BaseTbl.txnCode, BaseTbl.amount, BaseTbl.createdDtm');
        $this->db->from('tbl_earnings  as BaseTbl');
        $this->db->join('tbl_deposits as Deposit', 'Deposit.id = BaseTbl.depositId','left');
        $this->db->where('Deposit.userId =', $userId);
        $this->db->where('BaseTbl.createdDtm <=', $date);
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($searchText));
            $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $this->db->order_by('createdDtm', 'DESC');
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get all earnings based on userId
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function allEarnings($searchText = '', $page, $segment, $date)
    {
        $this->db->select('BaseTbl.id, BaseTbl.depositId, BaseTbl.type, BaseTbl.txnCode, Deposit.userId, User.firstName, User.lastName, BaseTbl.amount, BaseTbl.createdDtm');
        $this->db->from('tbl_earnings  as BaseTbl');
        $this->db->join('tbl_deposits as Deposit', 'Deposit.id = BaseTbl.depositId','left');
        $this->db->join('tbl_users as User', 'User.userId = Deposit.userId','left');
        $this->db->where('BaseTbl.createdDtm <=', $date);
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->order_by('BaseTbl.createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function allEarningsListingCount($searchText = '', $date)
    {
        $this->db->select('BaseTbl.id, BaseTbl.depositId, BaseTbl.txnCode, Deposit.userId, User.firstName, User.lastName, BaseTbl.amount, BaseTbl.createdDtm');
        $this->db->from('tbl_earnings  as BaseTbl');
        $this->db->join('tbl_deposits as Deposit', 'Deposit.id = BaseTbl.depositId','left');
        $this->db->join('tbl_users as User', 'User.userId = Deposit.userId','left');
        $this->db->where('BaseTbl.createdDtm <=', $date);
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function allWithdrawals($searchText = '', $page, $segment, $role)
    {
        $this->db->select('*');
        $this->db->from('tbl_withdrawals as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->order_by('BaseTbl.createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function allWithdrawalsListingCount($searchText = '')
    {
        $this->db->select('*');
        $this->db->from('tbl_withdrawals  as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $this->db->group_start();
            $terms = explode(' ', $searchText);
            if(count($terms)>1){
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[1]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
            }else{
                $this->db->or_like('BaseTbl.amount', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.firstName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('User.lastName', $this->db->escape_like_str($terms[0]));
                $this->db->or_like('BaseTbl.txnCode', $this->db->escape_like_str($terms[0]));
            }
            $this->db->group_end();
        }
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function withdrawalsById($searchText = '', $page, $segment, $userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_withdrawals');
        $this->db->where('userId =', $userId);
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('amount', $this->db->escape_like_str($searchText));
            $this->db->or_like('txnCode', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $this->db->order_by('createdDtm', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userWithdrawalsListingCount($searchText = '', $userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_withdrawals');
        $this->db->where('userId =', $userId);
        if(!empty($searchText)) {
            $this->db->group_start();
            $this->db->or_like('amount', $this->db->escape_like_str($searchText));
            $this->db->or_like('txnCode', $this->db->escape_like_str($searchText));
            $this->db->group_end();
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getWithdrawalInfo($withdrawalId)
    {
        $this->db->select('*');
        $this->db->from('tbl_withdrawals');
        $this->db->where('id', $withdrawalId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateWithdrawal($withdrawalInfo, $withdrawalId)
    {
        $this->db->where('id', $withdrawalId);
        $this->db->update('tbl_withdrawals', $withdrawalInfo);
        
        return TRUE;
    }

    /**
     * This function is used to get all earnings based on userId
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function referrals($userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.referrerId, User.firstName, BaseTbl.referredId, BaseTbl.createdDtm');
        $this->db->from('tbl_referrals  as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.referredId');
        $this->db->where('BaseTbl.referrerId =', $userId);

        $this->db->order_by('createdDtm', 'DESC');
        $query = $this->db->get();
        
        //$result = $query->result();        
        return $query;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewDeposit($userId, $depositInfo, $earningsAmount, $start, $end, $payoutsInterval, $maturity, $businessDays)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_deposits', $depositInfo);
        
        $result = $this->db->insert_id();

        if($result > 0)
        {
            if($payoutsInterval != $maturity)
            {
                $dates = $this->getDatesFromRange($start, $end, $payoutsInterval, $businessDays);
                foreach($dates as $date) {
                    $array[] = array(
                        'type' => 'interest',
                        'userId'=> $userId,
                        'depositId' => $result,
                        'txnCode' => 'PO'.random_string('alnum',8),
                        'amount' => $earningsAmount,
                        'createdDtm'=>$date
                    );
                };

                $this->db->insert_batch('tbl_earnings', $array);
                
                $insert_id = $this->db->insert_id();
                $result1 = $insert_id;
            } else {
                $array = array(
                    'type' => 'interest',
                    'userId'=> $userId,
                    'depositId' => $result,
                    'txnCode' => 'PO'.random_string('alnum',8),
                    'amount' => $earningsAmount,
                    'createdDtm'=>$end
                );
                $this->db->insert('tbl_earnings', $array);

                $insert_id = $this->db->insert_id();
                $result1 = $insert_id;
            }
        }

        if($result1 == 0){
            $this->db->delete('tbl_deposits', array('id'=>$result));
            $result = 0;
        }
        
        $this->db->trans_complete();
        
        return $result;
    }

    function updateDeposit($userId, $depositId, $depositInfo, $earningsAmount, $start, $end, $payoutsInterval, $maturity, $businessDays)
    {
        $this->db->trans_start();

        $this->db->where('id', $depositId);
        $result = $this->db->update('tbl_deposits', $depositInfo);
        $this->db->delete('tbl_earnings', array('depositId'=>$depositId, 'type'=>'interest'));

        if($result > 0)
        {
            if($payoutsInterval != $maturity)
            {
                $dates = $this->getDatesFromRange($start, $end, $payoutsInterval, $businessDays);
                foreach($dates as $date) {
                    $array[] = array(
                        'type' => 'interest',
                        'userId'=> $userId,
                        'depositId' => $depositId,
                        'txnCode' => 'PO'.random_string('alnum',8),
                        'amount' => $earningsAmount,
                        'createdDtm'=>$date
                    );
                };

                $this->db->insert_batch('tbl_earnings', $array);
                
                $insert_id = $this->db->insert_id();
                $result1 = $insert_id;
            } else {
                $array = array(
                    'type' => 'interest',
                    'userId'=> $userId,
                    'depositId' => $depositId,
                    'txnCode' => 'PO'.random_string('alnum',8),
                    'amount' => $earningsAmount,
                    'createdDtm'=>$end
                );
                $this->db->insert('tbl_earnings', $array);

                $insert_id = $this->db->insert_id();
                $result1 = $insert_id;
            }
        }

        if($result1 == 0){
            $this->db->delete('tbl_deposits', array('id'=>$result));
            $result = 0;
        }
        
        $this->db->trans_complete();
        
        return $result;
    }

    function getDatesFromRange($start, $end, $payoutsInterval, $businessDays, $format = 'Y-m-d H:i:s') {
        $array = array();
        $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
        //$holidayDays = ['*-12-25', '*-01-01', '2013-12-23']; # variable and fixed holidays
        $interval = 'PT'.$payoutsInterval.'H';
        $interval = new DateInterval($interval);
    
        $startDate = new DateTime($start);
        $realEnd = new DateTime($end);
        //$realEnd->add($interval);
    
        $periods = new DatePeriod($startDate, $interval, $realEnd);

        if($businessDays == 1){
            foreach($periods as $period) { 
                if (!in_array($period->format('N'), $workingDays)) continue;
                //if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
                //if (in_array($period->format('*-m-d'), $holidayDays)) continue;
                $array[] = $period->format($format); 
            }
        } else {
            foreach($periods as $period) { 
                $array[] = $period->format($format); 
            }
        }
    
        return $array;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editDeposit($depositInfo, $depositId)
    {
        $this->db->where('id', $depositId);
        $this->db->update('tbl_deposits', $depositInfo);
        
        $result = $this->db->affected_rows();
        
        return $result;
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function getDepositInfoById($depositId)
    {
        $this->db->select('*');
        $this->db->from('tbl_deposits');
        $this->db->where('id', $depositId);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getDeposit($txnCode, $userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_deposits');
        $this->db->where('txnCode', $txnCode);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewWithdrawal($withdrawalInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_withdrawals', $withdrawalInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

     /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewEarnings($earningsInfo)
    {
        $this->db->trans_start();
        $this->db->insert_batch('tbl_earnings', $earningsInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewEarning($earningsInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_earnings', $earningsInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function used to get account total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getWithdrawalsTotal($userId)
    {
        $this->db->select_sum('amount');
        $this->db->where('userId', $userId);
        $this->db->where('status', 1);
        $result = $this->db->get('tbl_withdrawals')->row();  
        return $result->amount;     
    }

    /**
     * This function used to get account total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getAllWithdrawalsTotal($startDate, $endDate, $status)
    {
        $this->db->select_sum('amount');
        $this->db->where('createdDtm >=', $startDate);
        $this->db->where('createdDtm <=', $endDate);
        $this->db->where('status', $status);
        $result = $this->db->get('tbl_withdrawals')->row();  
        return $result->amount;     
    }

    /**
     * This function used to get account total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getPendingWithdrawalsTotal($userId)
    {
        $this->db->select_sum('amount');
        if($userId != null){
        $this->db->where('userId', $userId);
        }
        $this->db->where('status', 0);
        $result = $this->db->get('tbl_withdrawals')->row();  
        return $result->amount;     
    }

    function depositsById($userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_deposits  as BaseTbl');
        $this->db->where('userId =', $userId);
        $this->db->order_by('createdDtm', 'DESC');
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function used to get earnings total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getEarningsTotal($userId)
    {
        $this->db->select_sum('amount');
        if($userId != null){
        $this->db->where('userId', $userId);
        }
        $this->db->where('createdDtm <=', date('Y-m-d H:i:s'));
        $result = $this->db->get('tbl_earnings')->row();  
        return $result->amount;
    }

    function getApprovedWithdrawalsTotal()
    {
        $this->db->select_sum('amount');
        $this->db->where('status', 1);
        $result = $this->db->get('tbl_withdrawals')->row();  
        return $result->amount;
    }

    function getWithdrawableDeposits($userId)
    {
        $this->db->select('BaseTbl.userId, Plan.id, sum(BaseTbl.amount) as totalAmount');
        $this->db->from('tbl_deposits as BaseTbl');
        $this->db->join('tbl_plans as Plan', 'BaseTbl.planId = Plan.id');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->where('BaseTbl.status', 0);
        $this->db->where('BaseTbl.status !=', 3);
        $query = $this->db->get();

        if($query !== FALSE && $query->num_rows() > 0){
            foreach($query->result_array() as $row)
            {
            $result[] = $row;
            }
            return $result[0]['totalAmount'];
        }
    }

    /**
     * This function used to get earnings total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getEarningsTotalByType($userId, $type)
    {
        $this->db->select_sum('amount');
        if($userId != null){
        $this->db->where('userId', $userId);
        }
        $this->db->where('type', $type);
        $this->db->where('createdDtm <=', date('Y-m-d H:i:s'));
        $result = $this->db->get('tbl_earnings')->row();  
        return $result->amount;
    }

    function getEarningsEmails($key)
    {
        $this->db->select('Earnings.txnCode, Earnings.amount, Earnings.userId, BaseTbl.email, BaseTbl.firstName');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_earnings as Earnings', 'Earnings.userId = BaseTbl.userId','left');
        $this->db->where('email_sent', $key);
        $this->db->where('Earnings.createdDtm <=', date('Y-m-d H:i:s'));

        $result = $this->db->get()->result();  
        return $result;
    }

    function editEarning($code, $earningsInfo){
        $this->db->where('txnCode', $code);
        $this->db->update('tbl_earnings', $earningsInfo);
        
        return TRUE;
    }

    /**
     * This function used to get earnings total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getAllPayoutsTotal($startDate,$endDate)
    {
        $this->db->select_sum('amount');
        $this->db->where('createdDtm =', $startDate);
        $this->db->where('createdDtm <=', $endDate);
        $result = $this->db->get('tbl_earnings')->row();  
        return $result->amount;
    }

    /**
     * This function used to get earnings total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getActiveDeposits($userId)
    {
        $this->db->select_sum('amount');
        if($userId != null){
        $this->db->where('userId', $userId);
        }
        $this->db->where('maturityDtm >', date('Y-m-d H:i:s'));
        $result = $this->db->get('tbl_deposits')->row();  
        return $result->amount;
    }

    /**
     * This function used to get earnings total by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getInActiveDeposits($userId)
    {
        $this->db->select_sum('amount');
        if($userId != null){
            $this->db->where('userId', $userId);
        }
        $this->db->where('status =', 0);
        $this->db->where('maturityDtm <', date('Y-m-d H:i:s'));
        $result = $this->db->get('tbl_deposits')->row();  
        return $result->amount;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deletePlan($planId)
    {
        $this->db->where('id', $planId);
        $this->db->delete('tbl_plans');
        
        return $this->db->affected_rows();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getPlans()
    {
        $this->db->select('id, name, minInvestment, maxInvestment, intAfterMaturity, principalReturn, profit, period, createdBy, createdDtm');
        $this->db->from('tbl_plans');
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function cancelDeposit($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $array = array(
            'status'=>5
        );
        $this->db->update('tbl_deposits', $array);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        } else {
            return true;
        }
    }

    function deleteDeposit($depositID)
    {
        $this->db->delete('tbl_deposits', array('id'=>$depositID));
        $result = $this->db->affected_rows();
        $this->db->delete('tbl_earnings', array('depositId'=>$depositID));

        $this->db->trans_complete();

        if($result > 0)
        {
            return true;
        }else
        {
            return false;
        }
    }

    function totalPayoutValue($depositID, $userID){
        $this->db->select_sum('amount');
        $this->db->where('depositId', $depositID);
        $this->db->where('userId', $userID);

        $query = $this->db->get('tbl_earnings');
    
        $totalEarnings = $query->result()[0]->amount;

        $this->db->select('*');
        $this->db->where('id', $depositID);
        $this->db->from('tbl_deposits');
        
        $query1 = $this->db->get();
        $depositAmount = $query1->row()->amount;

        return $totalEarnings + $depositAmount;
    }

    function create_deposit($array){
        $this->db->trans_start();
        $this->db->insert('tbl_deposits', $array);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

}