<?php

namespace My\Statistic;

use Emarref\Almanac\Annotation as Almanac;
use Emarref\Almanac\Statistic\AbstractStatistic;

/**
 * User Behaviour
 *
 * Information related to users and how they use the website.
 *
 * @Almanac\Statistic(source="my.source.mysql", renderer="markdown", destination="my.destination.filesystem")
 */
class UserStatistic extends AbstractStatistic
{
    /**
     * User Timezones
     *
     * User timezones grouped and ordered.
     *
     * @Almanac\Result
     * @Almanac\Filter("table")
     */
    public function getUserCount()
    {
        return '
            select time_zone, COUNT(id) as total_count
            from users
            group by time_zone
            order by total_count desc;
        ';
    }

    /**
     * User Blog Posts
     *
     * Average number of posts per user.
     *
     * @Almanac\Result
     * @Almanac\Filter("average")
     */
    public function getBlogPostAverages()
    {
        return '
            select (
                select count(id)
                from article
                where article.user_id = users.id
            ) as user_count
            from users
            order by user_count desc
        ';
    }
}
