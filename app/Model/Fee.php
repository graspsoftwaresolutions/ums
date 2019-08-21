<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model {

    protected $table = 'fee';
    protected $fillable = ['id', 'fee_name', 'fee_amount','fee_shortcode','is_monthly_payment' ,'status'];
    public $timestamps = true;

    public function saveFeedata($data=array())
    {
        if (!empty($data['id'])) {            
            $savedata = Fee::find($data['id'])->update($data);
        } else {
            $savedata = Fee::create($data);
        }
        return $savedata;
    }

    public function getFee($select = array(), $where = array(), $or_where = array(),$orderby = array(), $limit="", $offset = 0) {
      //  \DB::enableQueryLog();
        $query = Fee::query();
        if ($select == "")
            $select = "fee.*";
        $query->select($select);
        if (is_array($where)) {
            if (!empty($where))
                $query->where($where);
        } elseif ($where != "") {
            $query->where($where);
        }
        if (!empty($or_where)) {
            $whereor = "";
            foreach ($or_where as $orw) {
                $query->orWhere($orw[0], $orw[1], $orw[2]);
             }
        }
        $query->where('status', '=', '1');
           if(is_array($orderby) && !empty($orderby)) {
            $query->orderBy($orderby[0], $orderby[1]);
       }
         if((int)$limit != 0) $query->limit($limit, $offset);
        $scrapper = $query->get();
        return $scrapper;
        //$query1 = \DB::getQueryLog();
    }

}
