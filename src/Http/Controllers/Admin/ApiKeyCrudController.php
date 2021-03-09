<?php

namespace Jargoud\LaravelApiKey\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Jargoud\LaravelApiKey\Enums\ApiKey\Restriction;
use Jargoud\LaravelApiKey\Http\Requests\Admin\ApiKey\StoreRequest;
use Jargoud\LaravelApiKey\Http\Requests\Admin\ApiKey\UpdateRequest;
use Jargoud\LaravelApiKey\Models\Backpack\ApiKey;
use Jargoud\LaravelApiKey\Providers\LaravelApiKeyServiceProvider;
use Prologue\Alerts\Facades\Alert;

/**
 * Class ApiKeyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ApiKeyCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     * @throws Exception
     */
    public function setup()
    {
        CRUD::setModel(
            config(LaravelApiKeyServiceProvider::NAMESPACE . '.backpack.model')
        );
        CRUD::setRoute(backpack_url('api-key'));
        CRUD::setEntityNameStrings('api key', 'api keys');
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return Response|RedirectResponse|array
     */
    public function store()
    {
        CRUD::hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = CRUD::validateRequest();
        CRUD::setRequest($request);

        // insert item in the db
        $item = CRUD::create(CRUD::getStrippedSaveRequest());

        // show a success message
        Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        CRUD::setSaveAction();

        return CRUD::performSaveAction($item->getKey());
    }

    /**
     * Update the specified resource in the database.
     *
     * @return Response|RedirectResponse|array
     */
    public function update()
    {
        CRUD::hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = CRUD::validateRequest();
        CRUD::setRequest($request);

        // update the row in the db
        $item = CRUD::update(
            $request->get(CRUD::getModel()->getKeyName()),
            CRUD::getStrippedSaveRequest()
        );

        // show a success message
        Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        CRUD::setSaveAction();

        return CRUD::performSaveAction($item->getKey());
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        $this->setListColumns();
    }

    protected function setListColumns(): self
    {
        CRUD::addColumns([
            [
                'name' => ApiKey::COLUMN_NAME,
            ],
            [
                'name' => ApiKey::COLUMN_PREFIX,
            ],
            [
                'name' => ApiKey::COLUMN_RESTRICTION,
                'type' => 'select_from_array',
                'options' => Restriction::toSelectArray(),
            ],
        ]);

        return $this;
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(StoreRequest::class);

        $this->setCreateFields();
    }

    protected function setCreateFields(): self
    {
        CRUD::addFields([
            [
                'name' => StoreRequest::ATTRIBUTE_NAME,
            ],
            [
                'name' => StoreRequest::ATTRIBUTE_VALUE,
                'default' => Str::uuid(),
                'hint' => 'This value will be hashed on saving, you should copy it!',
                'attributes' => [
                    'readonly' => 'readonly',
                ],
            ],
            [
                'name' => StoreRequest::ATTRIBUTE_RESTRICTION,
                'type' => 'select_from_array',
                'options' => Restriction::toSelectArray(),
            ],
            [
                'name' => StoreRequest::ATTRIBUTE_REFERER,
                'type' => 'table',
                'view_namespace' => 'apikey::admin.api-key.fields',
            ],
            [
                'name' => StoreRequest::ATTRIBUTE_IP,
                'type' => 'table',
                'view_namespace' => 'apikey::admin.api-key.fields',
            ],
        ]);

        return $this;
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation(UpdateRequest::class);

        $this->setUpdateFields();
    }

    protected function setUpdateFields(): self
    {
        $this->setCreateFields();

        CRUD::removeField(StoreRequest::ATTRIBUTE_VALUE);

        return $this;
    }
}
