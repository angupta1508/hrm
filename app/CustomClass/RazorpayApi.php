<?php

namespace App\CustomClass;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OptimoApps\RazorPayX\Entity\Account;
use OptimoApps\RazorPayX\Entity\Bank;
use OptimoApps\RazorPayX\Entity\Contact;
use OptimoApps\RazorPayX\Entity\Payment;
use OptimoApps\RazorPayX\Enum\AccountTypeEnum;
use OptimoApps\RazorPayX\Enum\ContactTypeEnum;
use OptimoApps\RazorPayX\Enum\PaymentModeEnum;
use RazorPayX;
use Razorpay\Api\Api;

class RazorpayApi
{

    private $razorpayId;
    private $razorpayKey;
    private $razorpayAccount;
    private static $contact;

    public function __construct()
    {
        $this->razorpayId = config('razorpay_id');
        $this->razorpayKey = config('razorpay_key');
        $this->razorpayAccount = config('razorpay_account');
        Config::set('razorpay-x.key_id', $this->razorpayId);
        Config::set('razorpay-x.key_secret', $this->razorpayKey);
        Config::set('json-mapper.type', 'best-fit');
    }

    public function createOrderId($arry = [])
    {
        $result = [];
        if (!empty($arry['amount'])) {
            $receiptId = Str::random(20);
            $api = new Api($this->razorpayId, $this->razorpayKey);
            $order = $api->order->create(
                array(
                    'receipt' => $receiptId,
                    'amount' => $arry['amount'] * 100,
                    'currency' => !empty($arry['currency']) ? $arry['currency'] : 'INR',
                )
            );
            if (!empty($order['id'])) {
                // Return response on payment page
                $result = [
                    'status' => 1,
                    'orderId' => $order['id'],
                    'msg' => 'Success',
                ];
            } else {
                $result = [
                    'status' => 0,
                    'msg' => 'Something went wrong.',
                ];
            }
        } else {
            $result = [
                'status' => 0,
                'msg' => 'Amount field is required',
            ];
        }
        return $result;
    }

    public function fetchOrderId($order_id = '')
    {
        $result = [];
        if (!empty($order_id)) {

            try {
                $api = new Api($this->razorpayId, $this->razorpayKey);
                $res = $api->order->fetch($order_id)->payments()->toArray();
                $result = [
                    'status' => 1,
                    'data' => $res,
                    'msg' => 'Success',
                ];
            } catch (Exception $e) {
                $result = [
                    'status' => 0,
                    'msg' => 'Error: ' . $e->getMessage(),
                ];
            }
        } else {
            $result = [
                'status' => 0,
                'msg' => 'Order Id is required',
            ];
        }
        return $result;
    }

    public function createContact($user = [])
    {
        $return = [];
        // dd($user['name']);
        if (!empty($user['name'])) {
            if (!empty($user['mobile'])) {
                if (!empty($user['email'])) {
                    $contact = new Contact();
                    // $contact->name = '';
                    $contact->name = !empty($user['name']) ? $user['name'] : '';
                    $contact->email = !empty($user['email']) ? $user['email'] : '';
                    $contact->contact = !empty($user['mobile']) ? $user['mobile'] : '';
                    $contact->type = ContactTypeEnum::EMPLOYEE;
                    $contact->reference_id = !empty($user['user_uni_id']) ? $user['user_uni_id'] : '';
                    $contact->notes = [
                        // 'notes_key_1' => 'Tea, Earl Grey, Hot',
                        // 'notes_key_2' => 'Tea, Earl Grey… decaf.',
                    ];

                    try {
                        $data = RazorPayX::contact()->create($contact);
                        $return['status'] = 1;
                        $return['msg'] = "Success";
                        $return['data'] = $data->toArray();
                    } catch (Exception $e) {
                        $error = json_decode($e->getMessage(), true);
                        $return['status'] = 0;
                        $return['msg'] = "Failed: " . $error['error']['description'];
                    }
                } else {
                    $return['status'] = 0;
                    $return['msg'] = "Email Field is Required.";
                }
            } else {
                $return['status'] = 0;
                $return['msg'] = "Phone Field is Required.";
            }
        } else {
            $return['status'] = 0;
            $return['msg'] = "Name Field is Required.";
        }

        // $response = RazorPayX::contact()->fetch($contact);
        return $return;
    }

    public function createFundAccount($data = [])
    {
        $return = [];
        if (!empty($data['account_name'])) {
            if (!empty($data['ifsc_code'])) {
                if (!empty($data['account_no'])) {
                    $bankAccount = new Bank();
                    $bankAccount->name = $data['account_name'];
                    $bankAccount->account_number = $data['account_no'];
                    $bankAccount->ifsc = $data['ifsc_code'];
                    $account = new Account();
                    $account->contact_id = $data['contact_id'];
                    $account->account_type = AccountTypeEnum::BANK_ACCOUNT;
                    $account->bank_account = $bankAccount;

                    try {
                        $data = RazorPayX::account()->create($account);
                        $return['status'] = 1;
                        $return['msg'] = "Success";
                        $return['data'] = $data->toArray();
                    } catch (Exception $e) {
                        $error = json_decode($e->getMessage(), true);
                        $return['status'] = 0;
                        $return['msg'] = "Failed: " . $error['error']['description'];
                    }
                } else {
                    $return['status'] = 0;
                    $return['msg'] = "Account No , Blank Please Check. ";
                }
            } else {
                $return['status'] = 0;
                $return['msg'] = "IFSC , Blank Please Check.";
            }
        } else {
            $return['status'] = 0;
            $return['msg'] = "Account Name , Blank Please Check.";
        }

        return $return;
    }

    public function createPayout($data = [])
    {

        // purpose => refund,cashback,payout,salary, utility bill, vendor bill
        $return = [];

        if (!empty($data['gateway_fund_id'])) {
            if (!empty($data['amount'])) {
                $payment = new Payment();
                $payment->account_number = $this->razorpayAccount;
                $payment->fund_account_id = !empty($data['gateway_fund_id']) ? $data['gateway_fund_id'] : '';
                $payment->amount = !empty($data['amount']) ? $data['amount'] * 100 : '0';
                $payment->currency = !empty($data['currency']) ? $data['currency'] : 'INR';
                $payment->mode = PaymentModeEnum::IMPS;
                $payment->purpose = !empty($data['purpose']) ? $data['purpose'] : 'payout';
                $payment->narration = !empty($data['narration']) ? $data['narration'] : 'payout';
                // $payment->status = 'processed';
                // dd($payment);
                try {
                    $data = RazorPayX::payment()->create($payment);
                    // dd($data);die;
                    $return['status'] = 1;
                    $return['msg'] = "Success";
                    $return['data'] = $data->toArray();
                } catch (Exception $e) {
                    $error = json_decode($e->getMessage(), true);
                    $return['status'] = 0;
                    $return['msg'] = "Failed: " . $error['error']['description'];
                }
            } else {
                $return['status'] = 0;
                $return['msg'] = "Amount, Blank Please Check.";
            }
        } else {
            $return['status'] = 0;
            $return['msg'] = "Gateway Fund Id Blank Please Check.";
        }
        return $return;
    }
}
