<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\Core\Controller\AbstractController;
use MU\BloggingModule\Form\Type\ConfigType;

/**
 * Config controller base class.
 */
abstract class AbstractConfigController extends AbstractController
{
    /**
     * This method takes care of the application configuration.
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function configAction(Request $request)
    {
        if (!$this->get('mu_blogging_module.permission_helper')->hasPermission(ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }
        
        $form = $this->createForm(ConfigType::class, $this->get('mu_blogging_module.app_settings'));
        
        if ($form->handleRequest($request)->isValid()) {
            if ($form->get('save')->isClicked()) {
                $appSettings = $form->getData();
                $appSettings->save();
        
                $this->addFlash('status', $this->__('Done! Module configuration updated.'));
                $userName = $this->get('zikula_users_module.current_user')->get('uname');
                $this->get('logger')->notice('{app}: User {user} updated the configuration.', ['app' => 'MUBloggingModule', 'user' => $userName]);
            } elseif ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->__('Operation cancelled.'));
            }
        
            // redirect to config page again (to show with GET request)
            return $this->redirectToRoute('mubloggingmodule_config_config');
        }
        
        $templateParameters = [
            'form' => $form->createView()
        ];
        
        // render the config form
        return $this->render('@MUBloggingModule/Config/config.html.twig', $templateParameters);
    }
}
