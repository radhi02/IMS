<?php 
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Admin\RawMaterial;
use App\Models\Admin\MaterialRequirement;
use Notification as Notification;
use App\Notifications\OffersNotification as OffersNotification;
use Auth as Auth;

function get_routeName()
{
    $name =Route::currentRouteName();
    $perfect_rout= explode(".",$name);
    return str_replace('_',' ',ucfirst($perfect_rout[0]));
}

function checkRole($id)
{
    $role= Role::find($id);
    return $role->name;
}

function getcountry($country)
{
    if(is_numeric($country))
    {
        $getcountry = DB::table('countries')->select('name')->where('id',$country)->first();
        return $getcountry->name;
    }
    else
    {
        return false;
    }
}
function getstate($state)
{
    if(is_numeric($state))
    {
    $getstate = DB::table('states')->select('name')->where('id',$state)->first();
    return $getstate->name;
    }
    else
    {
        return false;
    }

}
function getcities($cities)
{
    if(is_numeric($cities))
    {
    $getcities = DB::table('cities')->select('name')->where('id',$cities)->first();
    return $getcities->name;
    }
    else
    {
        return false;
    }
}
function getdepartment($deptid)
{
    if(is_numeric($deptid))
    {
    $getdept = DB::table('departments')->select('department_name')->where('id',$deptid)->first();
    return $getdept->department_name;
    }
    else
    {
        return false;
    }
}
function getcustomer($customerId)
{
    if(is_numeric($customerId))
    {
    $getcustomer = DB::table('customers')->select('customer_name')->where('id',$customerId)->first();
    return $getcustomer->customer_name;
    }
    else
    {
        return false;
    }
}
function getUserData($userId)
{
    if(is_numeric($userId))
    {
    $getuser = DB::table('users')->select('Image')->where('id',$userId)->first();
    return $getuser->Image;
    }
    else
    {
        return false;
    }
}
function getItemName($itemList)
{
    if(is_array($itemList))
    {
    $getItems = DB::table('items')->select('name')->whereIn('id',$itemList)->pluck('name')->implode(', ');
    return $getItems;
    }
    else
    {
        return false;
    }
}


function CheckPermissionExitOrNot($Permisstion,$role_id)
{ 
    $get = checkRole($role_id);

    $main_selected_permission = DB::table('permissions as t1')->Join('role_has_permissions as t2','t1.id','=','t2.permission_id')
    ->where('t2.role_id',$role_id)->get();
 
    $array=array();

    foreach($main_selected_permission as $chekded_id)
    {
        $array[$chekded_id->id]=['cheked'=>$chekded_id->id];
    }
    return  $array;
}
if (! function_exists('vendorLastID')) {
    function vendorLastID()
    {
        // $last = DB::table('vendors')->latest('id')->first('vendor_code');
        $last = DB::table('vendors')->latest('id')->pluck('vendor_code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last->vendor_code);
            $vendor_code = $last[1];
            $get_perfectLast_id  = $vendor_code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'VND-'.$get_perfectLast_id;
    }
}

if (! function_exists('customerLastID')) {
    function customerLastID()
    {
        // $last = DB::table('customers')->latest('id')->first('customer_code');
        $last = DB::table('customers')->latest('id')->pluck('customer_code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last->customer_code);
            $customer_code = $last[1];
            $get_perfectLast_id  = $customer_code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'CUS-'.$get_perfectLast_id;
    }
}

if (! function_exists('userLastID')) {
    function userLastID()
    {
        // $last = DB::table('users')->latest('id')->first('emp_code');
        $last = DB::table('users')->latest('id')->pluck('emp_code')->first();
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last->emp_code);
            $emp_code = $last[1];
            $get_perfectLast_id  = $emp_code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'USR-'.$get_perfectLast_id;
    }
}

if (! function_exists('demandNoteLastID')) {
    function demandNoteLastID()
    {
        // $last = DB::table('demandnote')->latest('id')->first('code');
        $last = DB::table('demandnote')->latest('id')->pluck('code')->first();

        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'DN-'.$get_perfectLast_id;
    }
}

if (! function_exists('IssueLastID')) {
    function IssueLastID()
    {
        // $last = DB::table('issuematerial')->latest('id')->first('code');
        $last = DB::table('issuematerial')->latest('id')->pluck('code')->first();

        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
            $get_perfectLast_id++;
        }
        if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'IS-'.$get_perfectLast_id;
    }
}

if (! function_exists('ConsumptionLastID')) {
    function ConsumptionLastID()
    {
        // $last = DB::table('consumption')->latest('id')->first('code');
        $last = DB::table('consumption')->latest('id')->pluck('code')->first();

        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'CONSUME-'.$get_perfectLast_id;
    }
}

if (! function_exists('customerLastID')) {
    function customerLastID()
    {
        // $last = DB::table('customers')->latest('id')->first('customer_code');
        $last = DB::table('customers')->latest('id')->pluck('customer_code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last->customer_code);
            $customer_code = $last[1];
            $get_perfectLast_id  = $customer_code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'CUS-'.$get_perfectLast_id;
    }
}

if (! function_exists('departmentLastID')) {
    function departmentLastID()
    {
        // $last = DB::table('departments')->latest('id')->first('department_code');
        $last = DB::table('departments')->latest('id')->pluck('department_code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last->department_code);
            $department_code = $last[1];
            $get_perfectLast_id  = $department_code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }

        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'DEP-'.$get_perfectLast_id;
    }
}

if (! function_exists('orderLastID')) {
    function orderLastID()
    {
        // $last = DB::table('sales_order')->latest('id')->first('code');
        $last = DB::table('sales_order')->latest('id')->pluck('code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }

        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'SO-'.$get_perfectLast_id;
    }
}

if (! function_exists('InvoiceLastID')) {
    function InvoiceLastID()
    {
        // $last = DB::table('invoice_order')->latest('id')->first('code');
        $last = DB::table('invoice_order')->latest('id')->pluck('code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }

        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'INV-'.$get_perfectLast_id;
    }
}

if (! function_exists('purchaseLastID')) {
    function purchaseLastID()
    {
        // $last = DB::table('purchase_order')->latest('id')->first('code');
        $last = DB::table('purchase_order')->latest('id')->pluck('code')->first();
        
        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
            $get_perfectLast_id++;
        }
         if($get_perfectLast_id == 0000)
        {
            $get_perfectLast_id=0001;
        }

        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);

        return 'PO-'.$get_perfectLast_id;
    }
}

if (! function_exists('saleForManuLastID')) {
    function saleForManuLastID($sId)
    {
        // $last = DB::table('sales_order')->where('id',$sId)->first('code');
        $last = DB::table('sales_order')->where('id',$sId)->pluck('code')->first();

        $get_perfectLast_id=0000;
        if(!empty($last))
        {
            $last = explode('-',$last);
            $code = $last[1];
            $get_perfectLast_id  = $code;
        }
        $get_perfectLast_id = str_pad($get_perfectLast_id, 4, '0', STR_PAD_LEFT);
        return 'MO-'.$get_perfectLast_id;
    }
}

if (! function_exists('manufacturingLastID')) {
    function manufacturingLastID($sId)
    {
        $last = DB::table('manufacture_order')->where('sales_order_id',$sId)->get()->count();
        $get_perfectLast_id = $last + 1;
        $get_perfectLast_id = str_pad($get_perfectLast_id, 2, '0', STR_PAD_LEFT);
        return $get_perfectLast_id;
    }
}

if (! function_exists('makelist')) {
    function makelist($sId,$pId)
    {
        $result = DB::table('manufacture_order')->where('sales_order_id',$sId)->where('product_id',$pId)->get();
        return $result;
    }
}

if (! function_exists('UnitName')) {
    function UnitName($id)
    {
        return $units = DB::table('units')->where('id',$id)->select('id','unit_name')->first();
    }
}
if (! function_exists('unique_code')) {
    function unique_code($limit)
    {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}

if (! function_exists('RawMaterialName')) {
    function RawMaterialName($id)
    {
        return $rawmaterial = DB::table('raw_material')->where('id',$id)->select('id','name','code','quantity','unit_id')->first();
    }
}

if (! function_exists('ProductDetail')) {
    function ProductDetail($id)
    {
        return $product = DB::table('product')->where('id',$id)->select('id','name','quantity','sku')->first();
    }
}

if (! function_exists('numberToRoman')) {

    /**
     * Converts a number to its roman presentation.
     **/ 
    function numberToRoman($num)  
    { 
        // Be sure to convert the given parameter into an integer
        $n = intval($num);
        $result = ''; 
    
        // Declare a lookup array that we will use to traverse the number: 
        $lookup = array(
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ); 
    
        foreach ($lookup as $roman => $value)  
        {
            // Look for number of matches
            $matches = intval($n / $value); 
    
            // Concatenate characters
            $result .= str_repeat($roman, $matches); 
    
            // Substract that from the number 
            $n = $n % $value; 
        } 

        return $result; 
    } 
}

function StoreNewDateFormat($date)
{
    if($date !=""){ return $newDate = date("Y-m-d", strtotime($date)); }else { return false; }
}
function ShowNewDateFormat($date)
{
    if($date !=""){ return date("d-m-Y h:i:s A",strtotime($date)); }else { return false; }
}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
if (! function_exists('MaterialRequirementActivity')) {
    function MaterialRequirementActivity($ActivityName,$data)
    {        
        if($ActivityName == "Demand-Note-Activity") {
            if(count($data) > 0) {
                foreach($data as $key=>$value) {
                    $RequirementData = MaterialRequirement::where('raw_id', $value['RawId'])->first();
                    if ($RequirementData == null) {
                        $RawQuantity= RawMaterial::where('id', $value['RawId'])->pluck('quantity')->first();
                        $create = MaterialRequirement::create([
                            'raw_id' => $value['RawId'],
                            'quantity' => $value['demandQun'],
                            'stock' => $RawQuantity,
                            'requirement' => $value['demandQun'],
                            'pending_po' => 0,
                            'new_po' => $value['demandQun'],
                            'wip' => 0,
                            'fg' => 0
                        ]);
                    } else {
                        $updateData = [
                            'quantity' => $value['demandQun'],
                            'requirement' => $RequirementData->requirement + $value['demandQun'],
                            'new_po' => $RequirementData->new_po + $value['demandQun']
                        ];
                        MaterialRequirement::where('id',$RequirementData['id'])->update($updateData);            
                    }                    
                }
            }
        } else if($ActivityName == "Issue-Note-Activity") {
            if(count($data) > 0) {
                foreach($data as $key=>$value) {
                    $RequirementData = MaterialRequirement::where('raw_id', $value['RawId'])->first();
                    if ($RequirementData == null) {
                        $RawQuantity= RawMaterial::where('id', $value['RawId'])->pluck('quantity')->first();
                        $create = MaterialRequirement::create([
                            'raw_id' => $value['RawId'],
                            'quantity' => $value['IssueQun'],
                            'stock' => $RawQuantity - $value['IssueQun'],
                            'requirement' => 0,
                            'pending_po' => 0,
                            'new_po' => 0,
                            'wip' => $value['IssueQun'],
                            'fg' => 0
                        ]);
                    } else {
                        $updateData = [
                            'stock' => $RequirementData->stock - $value['IssueQun'],
                            'quantity' => $value['IssueQun'],
                            'requirement' => $RequirementData->requirement - $value['IssueQun'],
                        ];
                        MaterialRequirement::where('id',$RequirementData['id'])->update($updateData);            
                    }
                }
            }
        } else if($ActivityName == "Consume-Note-Activity") {
            if(count($data) > 0) {
                foreach($data as $key=>$value) {
                    $RequirementData = MaterialRequirement::where('raw_id', $value['RawId'])->first();
                    if ($RequirementData == null) {
                        $RawQuantity= RawMaterial::where('id', $value['RawId'])->pluck('quantity')->first();
                        $create = MaterialRequirement::create([
                            'raw_id' => $value['RawId'],
                            'quantity' => $value['ConsumeQun'],
                            'stock' => $RawQuantity,
                            'requirement' => 0,
                            'pending_po' => 0,
                            'new_po' => 0,
                            'wip' => $value['ConsumeQun'],
                            'fg' => 0
                        ]);
                    } else {
                        $updateData = [
                            'quantity' => $value['ConsumeQun'],
                            'wip' => $RequirementData->wip + $value['ConsumeQun'],
                        ];
                        MaterialRequirement::where('id',$RequirementData['id'])->update($updateData);            
                    }
                }
            }
        } else if($ActivityName == "Purchase-Order-Activity") {
            if(count($data) > 0) {
                foreach($data as $key=>$value) {
                    $RequirementData = MaterialRequirement::where('raw_id', $value['rawId'])->first();
                    if ($RequirementData == null) {
                        $RawQuantity= RawMaterial::where('id', $value['rawId'])->pluck('quantity')->first();
                        $create = MaterialRequirement::create([
                            'raw_id' => $value['rawId'],
                            'quantity' => $value['rawqun'],
                            'stock' => $RawQuantity,
                            'requirement' => 0,
                            'pending_po' => $value['rawqun'],
                            'new_po' => 0,
                            'wip' => 0,
                            'fg' => 0
                        ]);
                    } else {
                        if($RequirementData->new_po == 0 || $RequirementData->new_po < $value['rawqun']) {
                            $tmpNewPO = 0; 
                        } else if($RequirementData->new_po > $value['rawqun']) {
                            $tmpNewPO = $RequirementData->new_po - $value['rawqun'];
                        }
                        $updateData = [
                            'quantity' => $value['rawqun'],
                            'pending_po' => $RequirementData->pending_po + $value['rawqun'],
                            'new_po' => $tmpNewPO,
                        ];
                        MaterialRequirement::where('id',$RequirementData['id'])->update($updateData);            
                    }
                }
            }
        }else if($ActivityName == "Purchase-Order-Receive-Activity") {
            if(count($data) > 0) {
                foreach($data as $key=>$value) {
                    $RequirementData = MaterialRequirement::where('raw_id', $value['raw_material_id'])->first();
                    if ($RequirementData == null) {
                        $RawQuantity= RawMaterial::where('id', $value['raw_material_id'])->pluck('quantity')->first();
                        $create = MaterialRequirement::create([
                            'raw_id' => $value['raw_material_id'],
                            'quantity' => $value['received_quantity'],
                            'stock' => $RawQuantity + $value['received_quantity'],
                            'requirement' => 0,
                            'pending_po' => $value['received_quantity'],
                            'new_po' => 0,
                            'wip' => 0,
                            'fg' => 0
                        ]);
                    } else {
                        $updateData = [
                            'quantity' => $value['received_quantity'],
                            'pending_po' => $RequirementData->pending_po - $value['received_quantity'],
                            'stock' => $RequirementData->stock + $value['received_quantity'],
                        ];
                        MaterialRequirement::where('id',$RequirementData['id'])->update($updateData);            
                    }
                }
            }
        }
        return true;
    }
    function br2nl($string)
    {
        $breaks = array("<br />","<br>","<br/>");  
        return str_ireplace($breaks, " ", $string); 
    }

    function getIndianCurrencyToWords(float $number)
	{
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'One', '2' => 'Two',
		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
		'13' => 'Thirteen', '14' => 'Fourteen',
		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
		'60' => 'Sixty', '70' => 'Seventy',
		'80' => 'Eighty', '90' => 'Ninety');
		$digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
		while( $i < $digits_length ) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
			} else $str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . " Only";
	}	


    if (! function_exists('FetchCompanyLogo')) {
        function FetchCompanyLogo()
        {
            $CompanyLogo = DB::table('companies')->pluck('logo')->first();
            return $CompanyLogo;
        }
    }
    

    if (! function_exists('SendCustomNotifications')) {
        function SendCustomNotifications($msg = '',$code = '',$route)
        {
            if($msg != '') {
                $userList = User::get();
                $offerData = [
                    'username' => ucfirst(auth()->user()->first_name).'  '.ucfirst(auth()->user()->last_name),
                    'body' => htmlspecialchars($msg),
                    'code' => $code,
                    'Url' => $route,
                ];
        
                foreach($userList as $user) {
                    Notification::send($user, new OffersNotification($offerData));
                }
            }
        }
    }
    
    

}
