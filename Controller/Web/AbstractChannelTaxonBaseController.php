<?php
//TODO: move to a proper place
namespace Toro\Bundle\AdminBundle\Controller\Web;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonsAwareInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Toro\Bundle\AdminBundle\Model\ChannelInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractChannelTaxonBaseController extends Controller
{
    public function indexAction(Request $request)
    {
        $page = $request->get('page') ?: 1;

        $data = $this->repository()->createPaginator(['taxons' => $this->getCurrentChannel()->getTaxons()->first()]);

        $data->setCurrentPage($page);

        return $this->render($this->getIndexTemplate(), array(
            'data' => $data,
        ));
    }

    public function showAction($id)
    {
        $resource = $this->checkResource($id);

        return $this->render($this->getShowTemplate(), array(
            'resource' => $resource,
        ));
    }

    protected function checkResource($id)
    {
        /** @var TaxonsAwareInterface $resource */
        if (!$resource = $this->repository()->find($id)) {
            throw new NotFoundHttpException();
        }

        $channel = $this->getCurrentChannel();

        $taxon = $channel->getTaxons()->first();

        if (!$resource->getTaxons()->contains($taxon)) {
            throw new NotFoundHttpException(sprintf(
                'Requested %s does not exist for channel: %s.',
                get_class($resource),
                $channel->getName()
            ));
        }

        return $resource;
    }

    /**
     * @return ChannelInterface
     */
    protected function getCurrentChannel()
    {
        return $this->get('sylius.context.channel')->getChannel();
    }

    /**
     * @return EntityRepository
     */
    abstract protected function repository();

    /**
     * @return string
     */
    abstract protected function getShowTemplate();

    /**
     * @return string
     */
    abstract protected function getIndexTemplate();
}
