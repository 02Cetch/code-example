<?php

namespace App\Factory;

use App\Entity\UserSkill;
use Symfony\Component\String\Slugger\AsciiSlugger;

class UserSkillFactory
{
    public function create(string $title, string $name = null, int $weight = UserSkill::DEFAULT_WEIGHT): UserSkill
    {
        $skill = new UserSkill();
        $skill->setTitle($title);

        if ($name) {
            $skill->setName($name);
        } else {
            $slugger = new AsciiSlugger('ru_RU');
            $skill->setName($slugger->slug($title)->lower());
        }

        $skill->setWeight($weight);
        return $skill;
    }
}
