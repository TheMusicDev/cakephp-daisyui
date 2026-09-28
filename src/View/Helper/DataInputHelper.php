<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;

/**
 * daisyUI "Data input" components.
 *
 * Every component in this category is owned by the framework's FormHelper;
 * each method exists for discoverability only and throws.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class DataInputHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Redirects to the Form helper method that owns this component.
     *
     * @param string $method Name of the daisyUI component method that was called.
     * @return never
     * @throws \BadMethodCallException Always.
     */
    private function redirect(string $method): never
    {
        throw new BadMethodCallException(sprintf(
            'The "%s" component is owned by the Form helper: $this->Form->control($field, ["type" => "..."]).',
            $method,
        ));
    }

    /**
     * @return never
     */
    public function calendar(): never
    {
        $this->redirect('calendar');
    }

    /**
     * @return never
     */
    public function checkbox(): never
    {
        $this->redirect('checkbox');
    }

    /**
     * @return never
     */
    public function fieldset(): never
    {
        $this->redirect('fieldset');
    }

    /**
     * @return never
     */
    public function fileInput(): never
    {
        $this->redirect('file-input');
    }

    /**
     * @return never
     */
    public function filter(): never
    {
        $this->redirect('filter');
    }

    /**
     * @return never
     */
    public function label(): never
    {
        $this->redirect('label');
    }

    /**
     * @return never
     */
    public function radio(): never
    {
        $this->redirect('radio');
    }

    /**
     * @return never
     */
    public function range(): never
    {
        $this->redirect('range');
    }

    /**
     * @return never
     */
    public function rating(): never
    {
        $this->redirect('rating');
    }

    /**
     * @return never
     */
    public function select(): never
    {
        $this->redirect('select');
    }

    /**
     * @return never
     */
    public function input(): never
    {
        $this->redirect('input');
    }

    /**
     * @return never
     */
    public function textarea(): never
    {
        $this->redirect('textarea');
    }

    /**
     * @return never
     */
    public function toggle(): never
    {
        $this->redirect('toggle');
    }

    /**
     * @return never
     */
    public function validator(): never
    {
        $this->redirect('validator');
    }

    /**
     * @return never
     */
    public function otp(): never
    {
        $this->redirect('otp');
    }
}
