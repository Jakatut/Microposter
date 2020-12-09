<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

const DEFAULT_IMAGE_NAME="blank-profile-picture.png";

trait UserImage {

    public function getProfileImageURL($user) {
        if ($user === null) {
            return "";
        }

        $location = $this->getProfileImageName($user);
        $disk = Storage::disk('gcs');
        if ($disk->exists($location)) {
            $url = $disk->url($location);
        } else {
            $url = $disk->url(DEFAULT_IMAGE_NAME);
        }

        return $url;
    }

    public function getProfileImageName($user) {
        $location = "";
        if ($user !== null) {
            $profile = $user->profile()->get()->first();
            $location = $user->id . '-' . $user->name . '/' . $profile->image;
            $location = $profile->image ? $location : DEFAULT_IMAGE_NAME;
        }
        return $location;
    }

    public function getProfileImageUploadName($user) {
        $location = "";
        if ($user !== null) {
            $profile = $user->profile()->get()->first();
            $location = $user->id . '-' . $user->name . '/';
        }
        return $location;
    }

}