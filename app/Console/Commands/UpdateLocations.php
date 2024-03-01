<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;

class UpdateLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // ftp://ftp.ncbi.nlm.nih.gov/genomes/H_sapiens/GRCh37.p13_interim_annotation/interim_GRCh37.p13_top_level_2017-01-13.gff3.gz
        // https://ftp.ncbi.nlm.nih.gov/genomes/refseq/vertebrate_mammalian/Homo_sapiens/all_assembly_versions/GCF_000001405.25_GRCh37.p13/GCF_000001405.25_GRCh37.p13_genomic.gff.gz
        echo "Importing ncbi location information ...\n";

		$handle = fopen(base_path() . '/data/hg19/GCF_000001405.25_GRCh37.p13_genomic.gff', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                if ($line[0] == '#')
                    continue;

                $parts = explode("\t", $line);

                if (substr($parts[0], 0, 2) != 'NC')
                    continue;

                if ($parts[2] != "gene")
                    continue;

                $split = preg_split('/[_\.]/', $parts[0]);

                $chr = ltrim($split[1], "0");
                $start = $parts[3];
                $stop = $parts[4];

                if ($chr > 24)
                {
                    $chr = null;
                    $start = null;
                    $stop = null;
                }

                // the HGNC ID lives here
                $moreparts = explode(";", $parts[8]);
                $k = strpos($moreparts[1], ',HGNC:');
                if ($k === false)
                    continue;

                $split = substr($moreparts[1], $k + 6 );
                $split = explode(',', $split);
                $hgncid = $split[0];

                //echo "Updating 37 " . $hgncid . "\n";

                $record = Gene::hgnc($hgncid)->first();

                if ($record !== null)
                {
                    if ($record->is_par)
                    {
                        // we update the par coords no matter what, but for backward compatibility we populate the legacy cords with X
                        if ($chr == 23)
                            $record->update(['chr' => $chr, 'start37' => $start, 'stop37' => $stop, 'seqid37' => $parts[0]]);

                        if ($record->par_coordinates === null)
                        {
                            $parcs = ['grch37' => ['x' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null],
                                                   'y' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null]],
                                      'grch38' => ['x' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null],
                                                   'y' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null]]];

                            $record->update(['par_coordinates' => $parcs]);
                        }

                        if ($chr  == 23)
                            $record->update(['par_coordinates->grch37->x' => 
                                                ['build' => 'GRCh37', 'chr' => $chr, 'start' => $start, 'stop' => $stop, 'seqid' => $parts[0]]]);
                        else
                            $record->update(['par_coordinates->grch37->y' => 
                                                ['build' => 'GRCh37', 'chr' => $chr, 'start' => $start, 'stop' => $stop, 'seqid' => $parts[0]]]);
                    }
                    else 
                    {
                        $record->update(['chr' => $chr, 'start37' => $start, 'stop37' => $stop, 'seqid37' => $parts[0]]);
                    }
                }

            }

            fclose($handle);
        }
        else
        {
            echo "(E001) Cannot access region37 file\n";
            exit;
        }

        // https://ftp.ncbi.nlm.nih.gov/genomes/all/GCF/000/001/405/GCF_000001405.39_GRCh38.p13/GCF_000001405.39_GRCh38.p13_genomic.gff.gz
        $handle = fopen(base_path() . '/data/hg38/GCF_000001405.40_GRCh38.p14_genomic.gff', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                if ($line[0] == '#')
                    continue;

                $parts = explode("\t", $line);

                if (substr($parts[0], 0, 2) != 'NC')
                    continue;

                if ($parts[2] != "gene")
                    continue;

                $split = preg_split('/[_\.]/', $parts[0]);

                $chr = ltrim($split[1], "0");
                $start = $parts[3];
                $stop = $parts[4];

                if ($chr > 24)
                {
                    $chr = null;
                    $start = null;
                    $stop = null;
                }

                // the HGNC ID lives here
                $moreparts = explode(";", $parts[8]);
                $k = strpos($moreparts[1], ',HGNC:');
                if ($k === false)
                    continue;

                $split = substr($moreparts[1], $k + 6 );
                $split = explode(',', $split);
                $hgncid = $split[0];

                //echo "Updating 38 " . $hgncid . "\n";

                $record = Gene::hgnc($hgncid)->first();

                if ($record !== null)
                {
                    if ($record->is_par)
                    {
                        if ($chr == 23)
                            $record->update(['chr' => $chr, 'start37' => $start, 'stop37' => $stop, 'seqid37' => $parts[0]]);

                        if ($record->par_coordinates === null)
                        {
                            $parcs = ['grch37' => ['x' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null],
                                                   'y' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null]],
                                      'grch38' => ['x' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null],
                                                   'y' => ['build' => null, 'chr' => null, 'start' => null, 'stop' => null, 'seqid' => null]]];

                            $record->update(['par_coordinates' => $parcs]);
                        }

                        if ($chr  == 23)
                            $record->update(['par_coordinates->grch38->x' => 
                                                ['build' => 'GRCh38', 'chr' => $chr, 'start' => $start, 'stop' => $stop, 'seqid' => $parts[0]]]);
                        else
                            $record->update(['par_coordinates->grch38->y' => 
                                                ['build' => 'GRCh38', 'chr' => $chr, 'start' => $start, 'stop' => $stop, 'seqid' => $parts[0]]]);

                    }
                    else 
                    {
                        $record->update(['chr' => $chr, 'start38' => $start, 'stop38' => $stop, 'seqid38' => $parts[0]]);
                    }
                }
            }

            fclose($handle);
        }
        else
        {
            echo "(E001) Cannot access region38 file\n";
            exit;
        }

        echo "Ncbi location information update complete\n";

    }
}
