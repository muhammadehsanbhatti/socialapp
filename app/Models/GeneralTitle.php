<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralTitle extends Model
{
    use HasFactory;

    public function setTitleStatusAttribute($value)
    {
        // $this->attributes['title'] = $value;

        if ($value == 1) {
            $this->attributes['title_slug'] = 'career-status-position';
        }
        elseif ($value == 2) {
            $this->attributes['title_slug'] = 'professional-role';
        }
        elseif ($value == 3) {
            $this->attributes['title_slug'] = 'educational-information';
        }
        elseif ($value == 4) {
            $this->attributes['title_slug'] = 'specialty-skills';
        }
        elseif ($value == 5) {
            $this->attributes['title_slug'] = 'industry-vertical';
        }
        elseif ($value == 6) {
            $this->attributes['title_slug'] = 'user-report';
        }
        elseif ($value == 7) {
            $this->attributes['title_slug'] = 'university-name';
        }
        elseif ($value == 8) {
            $this->attributes['title_slug'] = 'degree-discipline';
        }
        $this->attributes['title_status'] = $value;
    }

    public function getTitleStatusAttribute($value)
    {
        if ($value == 1) {
            return $this->attributes['title_status'] = 'Career status position';
        }
        if ($value == 2) {
            return $this->attributes['title_status'] = 'Professional role';
        }
        if ($value == 3) {
            return $this->attributes['title_status'] = 'Educational information';
        }
        if ($value == 4) {
            return $this->attributes['title_status'] = 'Specialty skills';
        }
        if ($value == 5) {
            return $this->attributes['title_status'] = 'Industry vertical';
        }
        if ($value == 6) {
            return $this->attributes['title_status'] = 'User report';
        }
        if ($value == 7) {
            return $this->attributes['title_status'] = 'University Name';
        }
        if ($value == 8) {
            return $this->attributes['title_status'] = 'Degree Discipline';
        }
    }

    public function professionalRoleType()
    {
        return $this->hasMany(ProfRoleType::class)->orderBy('id', 'ASC')->with('profRoleTypeItem');
    }

    public function industryVerticalItem()
    {
        return $this->hasMany(IndustryVerticalItem::class)->whereIn('category_status', ['Industry','Both'])->orderBy('title', 'ASC');
    }

    public static function getGeneralTitle($posted_data = array())
    {
        $query = GeneralTitle::latest()
        ->with('professionalRoleType')
        ->with('industryVerticalItem')
        ;

        if (isset($posted_data['id'])) {
            $query = $query->where('general_titles.id', $posted_data['id']);
        }
        if (isset($posted_data['title'])) {
            $query = $query->where('general_titles.title', 'like', $posted_data['title'] . '%');
        }
        if (isset($posted_data['title_match'])) {
            $query = $query->where('general_titles.title', $posted_data['title_match']);
        }
        if (isset($posted_data['status'])) {
            $query = $query->where('general_titles.status', $posted_data['status']);
        }
        if (isset($posted_data['title_status'])) {
            $query = $query->where('general_titles.title_status', $posted_data['title_status']);
        }
        if (isset($posted_data['title_slug'])) {
            $query = $query->where('general_titles.title_slug', $posted_data['title_slug']);
        }

        if (isset($posted_data['title_status_in'])) {
            $query = $query->whereIn('general_titles.title_status', $posted_data['title_status_in']);
        }
        if (isset($posted_data['general_not_in'])) {
            $query = $query->whereNotIn('general_titles.id', $posted_data['general_not_in']);
        }

        $query->select('general_titles.*');

        $query->getQuery()->orders = null;
        if (isset($posted_data['orderBy_name'])) {
            $query->orderBy($posted_data['orderBy_name'], $posted_data['orderBy_value']);
        } else {
            $query->orderBy('id', 'ASC');
        }

        if (isset($posted_data['groupBy']) && $posted_data['groupBy']) {
            $query->groupBy($posted_data['groupBy']);
        }

        if (isset($posted_data['paginate'])) {
            $result = $query->paginate($posted_data['paginate']);
        } else {
            if (isset($posted_data['detail'])) {
                $result = $query->first();
            } else if (isset($posted_data['count'])) {
                $result = $query->count();
            }
            else {
                $result = $query->get();
            }
        }

        if(isset($posted_data['printsql'])){
            $result = $query->toSql();
            echo '<pre>';
            print_r($result);
            print_r($posted_data);
            exit;
        }
        return $result;
    }

    public function saveUpdateGeneralTitle($posted_data = array())
    {
        if (isset($posted_data['update_id'])) {
            $data = GeneralTitle::find($posted_data['update_id']);
        } else {
            $data = new GeneralTitle;
        }

        if (isset($posted_data['title'])) {
            $data->title = $posted_data['title'];
        }
        if (isset($posted_data['status'])) {
            $data->status = $posted_data['status'];
        }
        if (isset($posted_data['title_status'])) {
            $data->title_status = $posted_data['title_status'];
        }
        if (isset($posted_data['title_slug'])) {
            $data->title_slug = $posted_data['title_slug'];
        }

        $data->save();
        $data = GeneralTitle::getGeneralTitle([
            'detail' => true,
            'id' => $data->id,
        ]);
        return $data;
    }
    public static function deleteGeneralTitle($id=0)
    {
        $data = GeneralTitle::find($id);
        return $data->delete();
    }
}
