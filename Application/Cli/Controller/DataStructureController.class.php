<?php


namespace Cli\Controller;


use Task\Bll\DataStructure\Index;
use Task\Bll\DataStructure\Linear;
use Task\Bll\DataStructure\Linked;
use Task\Bll\DataStructure\MaxHeap;
use Task\Bll\DataStructure\Queue;
use Task\Bll\DataStructure\Stack;
use Task\Bll\DataStructure\Tree;
use Think\Controller;
/*
 * 数据结构
 */
class DataStructureController extends Controller
{

    public function index()
    {
        echo 'index';
    }

    public function linear()
    {
        $linear = new Linear();
        $linear->add_item(0,1);
        $linear->add_item(1,2);
        $linear->add_item(1,3);
        $linear->add_item(1,4);
        $linear->add_item(1,5);
        $linear->add_item(1,6);
        $linear->add_item(1,7);
        $linear->get_items();

        $linear->del_item(1);
        $linear->get_items();

        echo $linear->get_item(2)."\n";

        echo $linear->get_index(6)."\n";

        $linear->update_item(1,7);
        $linear->get_items();

        $linear->truncate();
        $linear->add_item(0,1);
        $linear->get_items();

    }

    public function linked()
    {
        $link = new Linked();

        $link->add_item(0,'zf');
        $link->add_item(1,20);
        $link->add_item(2,'cl');
        $link->add_item(3,'st');
        $link->add_item(4,'gz');
        $arr = $link->get_items();
        echo "All items: ".json_encode($arr)."\n";

        $length = $link->get_length();
        echo "length={$length}\n";

        $link->del_item(1);
        $arr = $link->get_items();
        echo "All items: ".json_encode($arr)."\n";

        $link->interrupt(2);
        $arr = $link->get_items();
        echo "All items: ".json_encode($arr)."\n";

        $link->truncate();
        $arr = $link->get_items();
        echo "All items: ".json_encode($arr)."\n";
    }

    public function stack()
    {
        $stack = new Stack();
        $stack->push(1);
        $stack->push(2);
        $stack->push(3);
        $stack->push(4);

        $arr = $stack->get_items();
        echo "All items: ".json_encode($arr)."\n";

        $value = $stack->pop();
        echo "pop value={$value}\n";
        $arr = $stack->get_items();
        echo "All items: ".json_encode($arr)."\n";
    }


    public function queue()
    {
        $queue = new Queue();
        $queue->push(1);
        $queue->push(2);
        $queue->push(3);
        $queue->push(4);
        $queue->push(5);

        $arr = $queue->get_items();
        echo "All items: ".json_encode($arr)."\n";

        $queue->shift();
        $arr = $queue->get_items();
        echo "All items: ".json_encode($arr)."\n";
    }

    public function tree()
    {
        $tree = new Tree(1);
        $left = $tree->setLeft(new Tree(2));
        $right = $tree->setRight(new Tree(3));

        $left->setLeft(new Tree(4));
        $left->setRight(new Tree(5));

        $right->setRight(new Tree(6));
//        print_r($tree);
        $tree->preOrder($tree);
        echo "\n";
        $tree->midOrder($tree);
        echo "\n";
        $tree->sufOrder($tree);
        echo "\n";

    }


    public function heap()
    {
        $max = MaxHeap::array2max(array(50, 10, 90, 30, 70, 40, 80, 60, 20));
        print_r($max->element);
        echo $max->size;
        $max->insert(5);
        print_r($max->element);
        $max->delete();
        print_r($max->element);
    }



    /*
     * 产生索引
     */
    public function makeIndex()
    {
        $filePath = dirname(realpath(APP_PATH))."/Public/Common/content/";
        Index::make(100000,$filePath);
    }

    /*
     * 无使用索引读数据
     */
    public function readIndex_1()
    {
        $filePath = dirname(realpath(APP_PATH))."/Public/Common/content/";
        Index::read_1($filePath);
    }


    /*
     * 使用索引读数据
     */
    public function readIndex_2()
    {
        $filePath = dirname(realpath(APP_PATH))."/Public/Common/content/";
        Index::read_2($filePath);
    }
}