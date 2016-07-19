<?php


class HotelDeleterModel extends Model
{
    
    private $id;
    
    public function __construct($hotel_id)
    {
        parent::__construct();
        $this->id = $hotel_id;
    }
    
    
    /**
     * 关联性删除各表中相应项目
     */
    public function deleteFromTable()
    {
        $hotel = M('Hotel');
        if($hotel->where('id='.$this->id)->find())
           $hotel->where('id='.$this->id)->delete();
        
        $hotel_impr = M('HotelImpr');
        if($hotel_impr->where('hotel_id='.$this->id)->find())
           $hotel_impr->where('hotel_id='.$this->id)->delete();
        
        $hotel_que = M('HotelQue');
        if($hotel_que->where('hotel_id='.$this->id)->find())
           $hotel_que->where('hotel_id='.$this->id)->delete();
        
        $hotel_room = M('HotelRoomType');
        $room = $hotel_room->where('hotel_id='.$this->id)->select();
        if($room) {
            
            foreach($room as $r)
               @unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__."/web/File/hotel_room_type/" . $r['logopath']);
        }
        $hotel_room->where('hotel_id='.$this->id)->delete();
        
        $hotel_pic = M('HotelPic');
        $pic = $hotel_pic->where('hotel_id='.$this->id)->select();
        if($pic) {
            
            foreach($pic as $p)
              @unlink($_SERVER['DOCUMENT_ROOT'].__ROOT__."/web/File/hotel_pic/" . $p['picpath']);
        }
        $hotel_pic->where('hotel_id='.$this->id)->delete();
        
        $hotel_areabelong = M('HotelAreabelong');
        if($hotel_areabelong->where('hotel_id='.$this->id)->find())
           $hotel_areabelong->where('hotel_id='.$this->id)->delete();
        
    }
}

?>
