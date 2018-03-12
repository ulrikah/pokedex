<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;

class CommentsModel extends Model {

    protected $table = "comments";


    public function getShoutBox(){
    	$model_DB = new CommentsModel();
        $comments = $model_DB->getShoutBoxFromDB();
    	
        foreach ($comments as $comment){
        	$comment->text = linkify($comment->text);
        }
    	return $comments; 
    }

    public function getShoutBoxFromDB(){

        return $this->query("SELECT t.id, t.created_at, t.user, t.text FROM (SELECT comments.id, comments.created_at, comments.user, comments.text FROM comments
                            ORDER BY comments.id DESC
                            LIMIT 5) AS t
                            ORDER BY t.id DESC");
    }
}


	// Made by breakermind and jasny :: jasny/linkify.php
    function linkify($value, $protocols = array('http', 'mail'), array $attributes = array(), $mode = 'normal')
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr = ' ' . $key . '="' . htmlentities($val) . '"';
        }
        
        $links = array();
        
        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
        
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':   $value = preg_replace_callback($mode != 'all' ? '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i' : '~([^\s<]+\.[^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $link  . '" onclick="return confirm_alert(this);">' . $link . '</a>') . '>'; }, $value); break;
                case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="mailto:' . $match[1]  . '" onclick="return confirm_alert(this);">' . $match[1] . '</a>') . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="https://twitter.com/' . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . '" onclick="return confirm_alert(this);">' . $match[0] . '</a>') . '>'; }, $value); break;
                default:        $value = preg_replace_callback($mode != 'all' ? '~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i' : '~([^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $match[1]  . '" onclick="return confirm_alert(this);">' . $match[1] .  '</a>') . '>'; }, $value); break;
            }
        }
        
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
    }