<?php

declare(strict_types=1);

namespace SharedKernel\UserInterface\Console;

use SharedKernel\Application\Demo\AsyncMessage\DemoAsyncEvent;
use SharedKernel\Application\Message\TraceableStampFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SendDemoAsyncEventCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly TraceableStampFactory $traceableStampFactory
    ) {
        parent::__construct();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int //phpcs:ignore
    {
        $message = new DemoAsyncEvent('horse', 20);
        $message->withStamp($this->traceableStampFactory->create());

        $this->bus->dispatch($message);

        return Command::SUCCESS;
    }
}
