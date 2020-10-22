<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RecordsQuestion Entity
 *
 * @property int $id
 * @property int $record_id
 * @property int $question_id
 * @property string|null $answer
 * @property string|null $correct
 * @property int|null $is_correct
 * @property int $score
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Record $record
 * @property \App\Model\Entity\Question $question
 */
class RecordsQuestion extends Entity
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
        'record_id' => true,
        'question_id' => true,
        'answer' => true,
        'correct' => true,
        'is_correct' => true,
        'score' => true,
        'created' => true,
        'record' => true,
        'question' => true,
    ];
}
