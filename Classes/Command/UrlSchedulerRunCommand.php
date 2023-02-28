<?php

//declare(strict_types = 1);
namespace AUBA\CmsCensus\Command;

use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UrlSchedulerRunCommand extends Command
{
    /**
     * urlRepository
     *
     * @var UrlRepository
     */
    protected $urlRepository;

    /**
     * @param UrlRepository $urlRepository
     */
    public function injectUrlRepository(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    protected function configure()
    {
        $this
            ->addArgument(
                'argumentPerCron',
                InputArgument::REQUIRED,
                'Add number of URLs check per Run'
            );
        $this
            ->addArgument(
                'sysfolderID',
                InputArgument::REQUIRED,
                'Sysfolder ID'
            );
    }
    /**
     * Initializes the command after the input has been bound and before the input is validated.
     *
     * @see InputInterface::input()
     * @see InputInterface::output()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        $argumentPerCron = $input->getArgument('argumentPerCron');
        $sysfolderID = $input->getArgument('sysfolderID');
        $searchResult = $this->urlRepository->fetchUrls($argumentPerCron, $sysfolderID);
        $apiRequest = GeneralUtility::makeInstance(RequestFactory::class);

        foreach ($searchResult as $url) {
            $file = 'http://' . $url['name'];
            $file_headers = @get_headers($file);

            if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 502 Connection refused') {
                $status = 404;
            } else {
                $additionalOptions = [
                    'headers' => ['Cache-Control' => 'no-cache'],
                    'allow_redirects' => false,
                ];
                $apiResponse = $apiRequest->request('http://' . $url['name'], 'GET', $additionalOptions);
                $status = $apiResponse->getStatusCode();
            }

            $this->urlRepository->updateStatus((int)$url['uid'], (int)$status);
            $uid = (int)$url['uid'];
        }
        $this->urlRepository->updateFlag($uid, $sysfolderID);

        return COMMAND::SUCCESS;
    }
}
