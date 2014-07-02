<?php namespace License\Repositories;


class TransactionRepository 
{

	public function create($data)
	{
		$transaction = new Transaction;
		$transaction->ik_co_id 		= $data['ik_co_id'];
		$transaction->ik_co_prs_id 	= $data['ik_co_prs_id'];
		$transaction->ik_inv_id		= $data['ik_inv_id'];
		$transaction->ik_inv_st 	= $data['ik_inv_st'];
		$transaction->ik_inv_crt 	= $data['ik_inv_crt'];
		$transaction->ik_inv_prc 	= $data['ik_inv_prc'];
		$transaction->ik_trn_id 	= $data['ik_trn_id'];
		$transaction->ik_pm_no 		= $data['ik_pm_no'];
		$transaction->ik_desc 		= $data['ik_desc'];
		$transaction->ik_pw_via 	= $data['ik_pw_via'];
		$transaction->ik_am 		= $data['ik_am'];
		$transaction->ik_cur 		= $data['ik_cur'];
		$transaction->ik_co_rfn 	= $data['ik_co_rfn'];
		$transaction->ik_ps_price 	= $data['ik_ps_price'];
		$transaction->ik_sign 		= $data['ik_sign'];
		
		return $transaction->save();
	}

}