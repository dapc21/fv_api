<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

/**
 * A summary informing the user what the associated element does.
 *
 * A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
 * and to provide some background information or textual references.
 *
 * @version
 * @author
 * @license
 * @copyright
 */
class PruebaController extends Controller {

    use \App\Http\Controllers\Generic\ControllerTraitEdit;

    protected $modelo = 'App\Models\Test\Vehiculo';

}
