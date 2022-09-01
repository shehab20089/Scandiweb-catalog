<?php
namespace CatalogApp\Product\Setup\Patch\Data;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\State;
use Magento\Catalog\Api\CategoryLinkManagementInterface;

class AddSimpleProduct2 implements DataPatchInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    protected CategoryLinkManagementInterface $categoryLink;


    /**
     * CreateSimpleProduct constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ProductInterfaceFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ProductInterfaceFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface $storeManager,
        State $state,
        CategoryLinkManagementInterface $categoryLink

    )
    {
        $this->moduleDataSetup    = $moduleDataSetup;
        $this->productFactory     = $productFactory;
        $this->productRepository  = $productRepository;
        $this->categoryFactory    = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager       = $storeManager;
        $this->categoryLink = $categoryLink;
        $state->setAreaCode('adminhtml');
    }

    /**
     * @return string
     */
    public function apply()
    {
        $product = $this->productFactory->create();

        $simpleProductArray = [
            [
                'sku'               => 'shehab product2',
                'name'              => 'shehab product 2',
                'attribute_id'      => '4',
                'status'            => 1,
                'weight'            => 2,
                'price'             => 0,
                'visibility'        => 1,
                'type_id'           => 'simple',
            ]
        ];

        foreach ($simpleProductArray as $data) {
            // Create Product
            $product = $this->productFactory->create();
            $product->setSku($data['sku'])
                ->setName($data['name'])
                ->setAttributeSetId($data['attribute_id'])
                ->setStatus($data['status'])
                ->setWeight($data['weight'])
                ->setPrice($data['price'])
                ->setVisibility($data['visibility'])
                ->setTypeId($data['type_id'])
                ->setStockData(
                    array(
                        'use_config_manage_stock' => 0,
                        'manage_stock' => 1,
                        'is_in_stock' => 1,
                        'qty' => 199
                    )
                );
            $product = $this->productRepository->save($product);
            $product->save();
            $this->categoryLink->assignProductToCategories($product->getSku(), [2]);

        }
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public static function getVersion()
    {
        return '2.0.0';
    }
}