<?php
/**
* Import CUstomerSetupFactory
**/
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerFactory
     */
    private $customerSetupFactory;

    /**
     * @param CustomerFactory $customerSetupFactory
     */
    public function __construct(CustomerFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '1.0.0', '<')) {
            // ...
        }

        /** @var Customer $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();
        $customerSetup->installEntities();
        /* ... */
    }
}