<?php

class HotelModel extends Model {

    public function areas(int $id) {
        $city_b = M('HotelCitybelong');
        $list = array();
        for ($i = 0; $i < 6; $i++) {
            $temp = M("CityBelong")->table(M("CityBelong")->getTableName() . " cityb")
                            ->join(M("Area")->getTableName() . " a ON a.id=cityb.cid")
                            ->where("a.id=$id")->getField("cityb.id");
            $temp = ($temp) ? $temp : 0;
            $list[$i] = $city_b->table(M("HotelCitybelong")->getTableName() . " city")
                            ->join(M("CityBelong")->getTableName() . " cityb ON cityb.id=city.city_id")
                            ->where("cityb.id=$temp and city.area_type=" . ($i + 1))->getField("city.id, city.area_name, city.city_id");
        }
        return $list;
    }

}