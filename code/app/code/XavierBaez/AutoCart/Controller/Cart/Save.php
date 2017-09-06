<?php
namespace XavierBaez\AutoCart\Controller\Cart;

/**
 * Class Index
 * @package XavierBaez\AutoCart\Controller\Cart
 */
class Save extends \Magento\Framework\App\Action\Action
{
    protected $_cart;
    protected $_product;
    protected $_resultPageFactory;

    /**
     * Save constructor.
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->_cart = $cart;
        $this->_product = $product;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     *
     */
    public function execute()
    {
        try {
            $params = array();
            $params['qty'] = '1';
            $productIds = $this->getRequest()->getParams();
            foreach ($productIds as $productId) {
                $product = $this->_product->load($productId);
                if ($product) {
                    $this->_cart->addProduct($product, $params);
                }
            }
            $this->_cart->save();
            $this->messageManager->addSuccess(__('Added to cart successfully'));
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Link generated does not contain actual product'));
        }
        $this->getResponse()->setRedirect('/checkout/cart/index');
    }
}