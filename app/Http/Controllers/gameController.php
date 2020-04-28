<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class gameController extends Controller
{
    //

    public function main()
    {
        if(Cache::store('memcached')->get('best') != null){
            return view('login')->with('best', Cache::store('memcached')->get('best'))->with('bestPlayer', Cache::store('memcached')->get('bestPlayer'));
        }else{
            return view('login');
        }
    }


    public function loginPost()
    {
    	if(preg_match("/^\w+$/", $_POST['name'])){
	    	if(Cache::store('memcached')->has($_POST['name']) == false){
                //正確答案初始化
				$ranIndex = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
				$ranArr = array_rand($ranIndex, 4);
				shuffle($ranArr);
				Cache::store('memcached')->forever($_POST['name'], $ranArr);
			}
			
			return view('game')->with('Ans', Cache::store('memcached')->get($_POST['name']))->with('user', $_POST['name']);
		}else{
			return redirect('/')->with('mes', '1');
		}
    }



    public function check()
    {
    	//$ans = $request->input('ans');

    	$ans = $_POST['ans'];    	
    	if(preg_match("/^[0-9]{4}$/i", $ans)){

    		 //玩家猜測次數初始化
    		 $uCount = "_" . $_POST['user'];
    		 //$ucount = "abc";
    		 if(Cache::store('memcached')->get($uCount) == null){
    		 	$count = 0;
    		 }else{
    		 	$count = Cache::store('memcached')->get($uCount);
    		 	//$count = 1;
    		 }

    		

    		//遊戲判斷
    		$answer = preg_split('//', $ans, -1, PREG_SPLIT_NO_EMPTY);

            //判斷使用者不能輸入相同數字
    		for($a = 0; $a < 4; $a++){
    			for($b = $a + 1; $b < 4; $b++){
    				if($answer[$a] == $answer[$b]){
    					//return "Do not enter same word";
                        $res = "不能輸入相同數字";
                        $cRes = $count . "次";
                        $resArr = array("ansRes"=>$res, "couRes"=>$cRes); 
                        return json_encode($resArr);
    				}
    			}
    		}

            //玩家猜測次數
    		$corAns = Cache::store('memcached')->get($_POST['user']);
            $count++;
            Cache::store('memcached')->put($uCount, $count);

    		//判斷A狀況
    		$numA = 0;
    		for($i = 0; $i < 4; $i++){
    			if($corAns[$i] == $answer[$i]){
    				$numA++;
    			}
    		}


    		//判斷B狀況
    		$numB = 0;
    		for($k = 0; $k < 4; $k++){
    			for($j = 0; $j < 4; $j++){
    				if($corAns[$k] == $answer[$j] && $k != $j){
    					$numB++;
    				}
    			}
    		}


    	}


    	if($numA == 4){
            //成功判定
            $res = "Correct Answer";
            $cRes = $count . "次";
            $resArr = array("ansRes"=>$res, "couRes"=>$cRes); 
    		//return "Correct Answer " . $count . "次";
            return json_encode($resArr);
    	}else{
            //未成功判定。
            $res = $numA . "A" . $numB . "B";
            $cRes = $count . "次";
            $resArr = array("ansRes"=>$res, "couRes"=>$cRes);
    		//return $numA . "A" . $numB . "B " . $count . "次";
            return json_encode($resArr);
    	}
    	
    }


    public function reset()
    {
        //重新開始一局
    	Cache::store('memcached')->forget($_POST['player']);
    	Cache::store('memcached')->forget("_" . $_POST['player']);
        //Cache::store('memcached')->forget('best');

        if($_POST['clear'] == "1"){
            //插入最高紀錄
            if(isset($_POST['clearCount'])){
                if(Cache::store('memcached')->get('best') == null){
                    Cache::store('memcached')->forever('best', $_POST['clearCount']);
                    Cache::store('memcached')->forever('bestPlayer', $_POST['player']);
                }else{
                    $best = Cache::store('memcached')->get('best');
                    if($best > $_POST['clearCount']){
                        Cache::store('memcached')->forever('best', $_POST['clearCount']);
                        Cache::store('memcached')->forever('bestPlayer', $_POST['player']);
                    }
                }
            }
           
        }
        
        return redirect('/');
        
    }

}
