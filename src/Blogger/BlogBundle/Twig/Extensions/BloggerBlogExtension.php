<?php

namespace Blogger\BlogBundle\Twig\Extensions;

class BloggerBlogExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'created_ago' => new \Twig_Filter_Method($this, 'createdAgo'),
        );
    }

    public function createdAgo(\DateTime $dateTime) {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0)
            throw new \Exception("createdAgo is unable to handle dates in the future");
        $duration = "";
        if ($delta < 60) {
            // Segundos
            $time = $delta;
            $duration = "hace " . $time . " segundo" . (($time > 1) ? "s" : "");
        }
        else if ($delta <= 3600) {
            // Minutos
            $time = floor($delta / 60);
            $duration = "hace " . $time . " minuto" . (($time > 1) ? "s" : "");
        }
        else if ($delta <= 86400) {
            // Horas
            $time = floor($delta / 3600);
            $duration = "hace " . $time . " hora" . (($time > 1) ? "s" : "");
        }
        else {
            // Días
            $time = floor($delta / 86400);
            $duration = "hace " . $time . " dia" . (($time > 1) ? "s" : "");
        }

        return $duration;
    }
    
    public function getName() {
        return 'blogger_blog_extension';
    }
}