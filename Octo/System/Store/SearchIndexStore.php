<?php

/**
 * SearchIndex store for table: search_index
 */

namespace Octo\System\Store;

use b8\Database;
use Octo;
use Octo\Pages\Model;
use Octo\Store as StoreFactory;

/**
 * SearchIndex Store
 * @uses Octo\System\Store\Base\SearchIndexStoreBase
 */
class SearchIndexStore extends Octo\Store
{
    use Base\SearchIndexStoreBase;

    public function search($string)
    {
        $database = Database::getConnection('read');

        $words = $this->extractWords($string);
        $words = '\'' . implode('\', \'', array_keys($words)) . '\'';

        $query = 'SELECT model, content_id
                    FROM search_index
                    WHERE word IN ('.$words.')
                    GROUP BY model, content_id
                    ORDER BY SUM(instances) DESC LIMIT 20;';

        $stmt = $database->prepare($query);
        $rtn = [];

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(Database::FETCH_ASSOC);

            foreach ($res as $item) {
                $store = StoreFactory::get($item['model']);
                if($store) {
                    $rtn[] = $store->getByPrimaryKey($item['content_id']);
                }
            }
        }

        return $rtn;
    }

    public function updateSearchIndex($class, $contentId, $content)
    {
        $database = Database::getConnection('write');

        $stmt = $database->prepare('DELETE FROM search_index WHERE model = :model AND content_id = :id');
        $stmt->bindValue(':model', $class);
        $stmt->bindValue(':id', $contentId);
        $stmt->execute();

        $stmt = $database->prepare(
            'INSERT INTO search_index (word, model, content_id, instances) VALUES (:word, :model, :id, :count)'
        );
        $stmt->bindValue(':model', $class);
        $stmt->bindValue(':id', $contentId);

        foreach ($this->extractWords($content) as $word => $count) {
            $stmt->bindValue(':word', $word);
            $stmt->bindValue(':count', $count);
            $stmt->execute();
        }
    }

    protected function extractWords($string)
    {
        $alphabet = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,';
        $numbers = '0,1,2,3,4,5,6,7,8,9,';
        $words = 'about,above,above,across,after,afterwards,again,against,';
        $words .= 'all,almost,alone,along,already,also,although,always,am,among,';
        $words .= 'amongst,amoungst,amount,an,and,another,any,anyhow,anyone,anything,anyway,anywhere,are,around,as,';
        $words .= 'at,back,be,became,because,become,becomes,becoming,been,before,beforehand,behind,being,below,';
        $words .= 'beside,besides,between,beyond,bill,both,bottom,but,by,call,can,cannot,cant,co,con,could,couldnt,';
        $words .= 'cry,de,describe,detail,do,done,down,due,during,each,eg,eight,either,eleven,else,elsewhere,empty,';
        $words .= 'enough,etc,even,ever,every,everyone,everything,everywhere,except,few,fifteen,fify,fill,find,fire,';
        $words .= 'first,five,for,former,formerly,forty,found,four,from,front,full,further,get,give,go,had,has,hasnt,';
        $words .= 'have,he,hence,her,here,hereafter,hereby,herein,hereupon,hers,herself,him,himself,his,how,however,';
        $words .= 'hundred,ie,if,in,inc,indeed,interest,into,is,it,its,itself,keep,last,latter,latterly,least,less,';
        $words .= 'ltd,made,many,may,me,meanwhile,might,mill,mine,more,moreover,most,mostly,move,much,must,my,myself,';
        $words .= 'name,namely,neither,never,nevertheless,next,nine,no,nobody,none,noone,nor,not,nothing,now,nowhere,';
        $words .= 'of,off,often,on,once,one,only,onto,or,other,others,otherwise,our,ours,ourselves,out,over,own,part,';
        $words .= 'per,perhaps,please,put,rather,re,same,see,seem,seemed,seeming,seems,serious,several,she,should,';
        $words .= 'show,side,since,sincere,six,sixty,so,some,somehow,someone,something,sometime,sometimes,somewhere,';
        $words .= 'still,such,system,take,ten,than,that,the,their,them,themselves,then,thence,there,thereafter,';
        $words .= 'thereby,therefore,therein,thereupon,these,they,thickv,thin,third,this,those,though,three,through,';
        $words .= 'throughout,thru,thus,to,together,too,top,toward,towards,twelve,twenty,two,un,under,until,up,upon,';
        $words .= 'us,very,via,was,we,well,were,what,whatever,when,whence,whenever,where,whereafter,whereas,whereby,';
        $words .= 'wherein,whereupon,wherever,whether,which,while,whither,who,whoever,whole,whom,whose,why,will,with,';
        $words .= 'within,without,would,yet,you,your,yours,yourself,yourselves,the';
        
        $stopWords = explode(',', $alphabet . $numbers . $words);

        $string = strip_tags($string);
        $words = str_word_count($string, 1);

        $words = array_map(function ($item) use ($stopWords) {
            $item = strtolower($item);

            if (in_array($item, $stopWords)) {
                return false;
            } else {
                return $item;
            }
        }, $words);

        $rtn = array_count_values(array_filter($words));

        return $rtn;
    }
}
