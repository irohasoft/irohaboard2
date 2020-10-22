<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Log Entity
 *
 * @property int $id
 * @property string|null $log_type
 * @property string|null $log_content
 * @property int|null $user_id
 * @property string|null $user_ip
 * @property string|null $user_agent
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\User $user
 */
class Log extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'log_type' => true,
        'log_content' => true,
        'user_id' => true,
        'user_ip' => true,
        'user_agent' => true,
        'created' => true,
        'user' => true,
    ];
}
