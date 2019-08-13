<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Irc extends Model
{
   protected $table = "irc_confirmation";
   protected $fillable = ['id','resignedmemberno','resignedmembername','resignedmembericno','resignedmemberbankname','resignedmemberbranchname','resignedreason','ircmembershipno','ircname','ircposition',
                         'ircbank','ircbankaddress','irctelephoneno','ircmobileno','ircfaxno','promotedto','gradewef','nameofperson','waspromoted','beforepromotion','attached','herebyconfirm','filledby','nameforfilledby','branchcommitteeverification1','branchcommitteeverification2',
                        'branchcommitteeName','branchcommitteeZone','branchcommitteedate','remarks','status','submitted_at'];
                        
}
