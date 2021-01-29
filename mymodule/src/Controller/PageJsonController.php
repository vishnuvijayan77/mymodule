<?php
namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * My Module Controller.
 */
class PageJsonController extends ControllerBase {
   /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;
  
  /**
   * Constructs a new PageJsonController object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }
  
    public function getPageJson(NodeInterface $node) {
		// Get the node type.
        $type = $node->getType();
		// Get the siteapikey system variable.
		$config = $this->configFactory->get('system.site');
		$site_api = $config->get('siteapikey');	
		// Return node JSON if site api is set and node type is page.
		if (($site_api =! NULL ) && $type == 'page'){
			$response = $node->toArray();
			return new JsonResponse($response);
		}
		// Else show access denied page.
		else {
			throw new AccessDeniedHttpException();
		}
    }
}