<?php
namespace TypiCMS\Observers;

use Input;

use Croppa;

use FileUpload;

class FileObserver
{

    /**
     * On delete, unlink files and thumbs
     * 
     * @param  model $model eloquent
     * @return mixed false or void
     */
    public function deleted($model)
    {
        if (! $files = $model->files) {
            return;
        }

        foreach ($files as $filename) {
            Croppa::delete('/uploads/' . $model->getTable() . '/' . $model->$filename);
        }
    }

    /**
     * On save, upload files
     * 
     * @param  model $model eloquent
     * @return mixed false or void
     */
    public function saving($model)
    {
        if (! $files = $model->files) {
            return;
        }

        foreach ($files as $filename) {
            if (Input::hasFile($filename)) {
                // delete prev image
                $file = FileUpload::handle(Input::file($filename), 'uploads/' . $model->getTable());
                $model->$filename = $file['filename'];
            } else {
                $model->$filename = $model->getOriginal($filename);
            }
        }
    }

    /**
     * On update, delete previous file if changed
     * 
     * @param  model $model eloquent
     * @return mixed false or void
     */
    public function updated($model)
    {
        if (! $files = $model->files) {
            return;
        }

        foreach ($files as $filename) {

            // Nothing to do if file did not change
            if ($model->getOriginal($filename) == $model->filename) {
                return;
            }

            Croppa::delete('/uploads/' . $model->getTable() . '/' . $model->getOriginal($filename));

        }
    }
}