<?php

/**
 * Author: Dipesh79 <https://khanaldipesh.com.np>
 */

namespace Dipesh79\LaravelSoftDeleteAction\Traits;

use Dipesh79\LaravelSoftDeleteAction\Exception\InvalidActionOnDeleteException;
use Dipesh79\LaravelSoftDeleteAction\Exception\InvalidOnDeleteArrayException;
use Dipesh79\LaravelSoftDeleteAction\Exception\MissingDefaultForeignKeyValueMethodException;
use Dipesh79\LaravelSoftDeleteAction\Exception\MissingOnDeletePropertyException;
use Dipesh79\LaravelSoftDeleteAction\Exception\MissingSoftDeletesTraitException;
use Dipesh79\LaravelSoftDeleteAction\Exception\RestrictException;

/**
 * Trait HandlesSoftDeleteActions
 *
 * This trait provides functionality to handle soft delete actions in Laravel models.
 * It ensures that related models are handled appropriately when a model is soft deleted or restored.
 */
trait HandlesSoftDeleteActions
{
    /**
     * Boot the HandlesSoftDeleteActions trait.
     *
     * This method hooks into the deleting and restoring events of the model to handle related actions.
     *
     * @throws RestrictException
     * @throws MissingOnDeletePropertyException
     * @throws InvalidOnDeleteArrayException
     * @throws MissingSoftDeletesTraitException
     * @throws MissingDefaultForeignKeyValueMethodException
     * @throws InvalidActionOnDeleteException
     */
    public static function bootHandlesSoftDeleteActions(): void
    {
        // Check if the SoftDeletes trait is used
        self::checkSoftDeletesTrait(new self);
        // Validate the $onDelete property
        self::validateOnDeleteProperty(new self);

        static::deleting(function ($model) {
            // Handle the actual deleting logic based on $onDelete actions
            self::handleOnDeleteActions($model);
        });

        static::restored(function ($model) {
            self::handleRestoringActions($model);
        });
    }

    /**
     * Check if the SoftDeletes trait is used in the model.
     *
     * @param $model
     * @throws MissingSoftDeletesTraitException
     */
    protected static function checkSoftDeletesTrait($model): void
    {
        if (!in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
            throw new MissingSoftDeletesTraitException();
        }
    }

    /**
     * Validate that the $onDelete property exists and is an array.
     *
     * @param $model
     * @throws MissingOnDeletePropertyException
     * @throws InvalidOnDeleteArrayException
     */
    protected static function validateOnDeleteProperty($model): void
    {
        // Check if the getOnDeleteActions method exists in the model
        if (!property_exists($model, 'onDelete')) {
            throw new MissingOnDeletePropertyException(sprintf("The model %s must have an 'onDelete' property.",
                get_class($model)
            ));
        }

        // Ensure $onDelete is an array
        $onDeleteActions = $model->getOnDeleteActions();
        if (!is_array($onDeleteActions)) {
            throw new InvalidOnDeleteArrayException();
        }
    }

    /**
     * Get the $onDelete actions array from the model.
     *
     * @return array
     */
    public function getOnDeleteActions(): array
    {
        return $this->onDelete ?? [];
    }

    /**
     * Handle the delete actions based on the $onDelete property.
     *
     * @param $model
     * @throws RestrictException
     * @throws MissingDefaultForeignKeyValueMethodException
     * @throws InvalidActionOnDeleteException
     */
    protected static function handleOnDeleteActions($model): void
    {
        foreach ($model->getOnDeleteActions() as $relation => $action) {
            $relatedQuery = $model->$relation();
            switch ($action) {
                case 'cascade':
                    self::cascade($relatedQuery);
                    break;

                case 'setNull':
                    self::setNull($relatedQuery);
                    break;

                case 'restrict':
                    self::restrict($relatedQuery, $model);
                    break;

                case 'setDefault':
                    self::setDefault($model, $relatedQuery, $relation);
                    break;

                default:
                    throw new InvalidActionOnDeleteException(sprintf("Invalid action '%s' for relation '%s' in '%s' model.",
                        $action, $relation, get_class($model)
                    ));
            }
        }
    }

    /**
     * Delete the related models.
     *
     * @param $relatedQuery
     * @return void
     */
    public static function cascade($relatedQuery): void
    {
        $relatedQuery->get()->each->delete();
    }

    /**
     * Set the foreign key of related models to null.
     *
     * @param $relatedQuery
     * @return void
     */
    public static function setNull($relatedQuery): void
    {
        $relatedQuery->update([$relatedQuery->getForeignKeyName() => null]);
    }

    /**
     * Restrict the deletion if related models exist.
     *
     * @param $relatedQuery
     * @param $model
     * @return void
     * @throws RestrictException
     */
    public static function restrict($relatedQuery, $model): void
    {
        if ($relatedQuery->exists()) {
            throw new RestrictException(sprintf("Cannot delete this instance of model %s because it is related to instance of model %s.",
                get_class($model), get_class($relatedQuery->getModel())
            ));
        }
    }

    /**
     * Set the foreign key of related models to a default value.
     *
     * @param $model
     * @param $relatedQuery
     * @param int|string $relation
     * @return void
     * @throws MissingDefaultForeignKeyValueMethodException
     */
    public static function setDefault($model, $relatedQuery, int|string $relation): void
    {
        if (!method_exists($model, 'getDefaultForeignKeyValueForRelation')) {
            throw new MissingDefaultForeignKeyValueMethodException(sprintf("The model %s is missing 'getDefaultForeignKeyValueForRelation' method.",
                get_class($model)
            ));
        }
        $relatedQuery->update([$relatedQuery->getForeignKeyName() => $model->getDefaultForeignKeyValueForRelation($relation)]);
    }

    /**
     * Handle the restoring actions based on the $onDelete property.
     *
     * @param $model
     */
    protected static function handleRestoringActions($model): void
    {
        if (method_exists($model, 'getOnDeleteActions')) {
            foreach ($model->getOnDeleteActions() as $relation => $action) {
                if ($action === 'cascade') {
                    $model->$relation()->withTrashed()->get()->each->restore();
                }
            }
        }
    }
}
