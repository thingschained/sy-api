<?php


class VideoCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function getListTest(ApiTester $I)
    {
        $method = 'video/getList';
        $I->wantTo('start test getList');
        $I->sendPOST($method);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        if($I->seeResponseJsonMatchesXpath('//data') == '')
        {         
            $table = ['headers'=>['参数','类型','是否必要','含义'],'rows'=>[['参数'=>1,'类型'=>1,'是否必要'=>1,'含义'=>1]]];
            $array[0] = ['h2'=>'获取视频分类'];
            $array[] = ['h4'=>'接口地址'];
            $array[] = ['blockquote'=>'video/getList'];
            $array[] = ['h4'=>'请求方法'];
            $array[] = ['blockquote'=>$method];
            $array[] = ['h4'=>'请求参数'];
            $array[] = ['table'=>$table];
            $array[] = ['h4'=>'响应数据说明'];
            $array[] = ['table'=>$table];
            $array[] = ['h4'=>'响应数据'];
            $array[] = ['code'=>['language'=>'json','content'=>json_encode(json_decode($I->grabResponse()),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)]];

            file_put_contents('test.json', json_encode($array));
            exec( 'node /Users/jason/Desktop/dev/www/frame/Resource/json.js test.json');

        }
   
    }
}
