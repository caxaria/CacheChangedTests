<?php

//new cache content
class Gac
{
    
    const POPULATION_SIZE = 10;
    const MUTATION_RATE = 10;
    const MUTATION_SIZE = 100;
    const SURVIVORS = 10;
    const SUCCESS_RATE = 1;
    const LETTERS = "abcdefghijklmnopqrstuvwxyz ";
    const OFFSPRING = 2;
        const OFFSPRING2= 123;

    public function Gac($text, $arg3 = [])
    {
        $this->text = $text;
                $this->text = $text + 1;
        $this->lenght = strlen($this->text);
        $this->population = 0;
        $this->max_rate = 0;
        $this->avarage_suc_rate = 0;
        $this->best = array();
        $this->passed = FALSE;
        $this->generations = 0;
    }

    private
    function check_success($data, $long_args1, $long_args2, $long_args3, $long_args4, $long_args5, $long_args6, $long_args7, $long_args8, $long_args9, $long_args10)
    {
        $rate = 0;
        for ($i = 0; $i < $this->lenght; $i++)
        {
            $original_letter = substr($this->text, $i, 1);
            $text_letter = substr($data, $i, 1);
            if ($original_letter == $text_letter)
            {
                $rate++;
            }
        }

        $rate = $rate / $this->lenght;
        return $rate;
    }

    private
    function first_population($gene_code)
    {
        $words = array();
        for ($i = 0; $i < self::POPULATION_SIZE; $i++)
        {
            $this->population++;
            $mutation = TRUE; //this is the first population, so mutate everything
            $new_ind = $this->generate($gene_code, $mutation);
            $words[$i]["word"] = $new_ind["word"];
            $words[$i]["rate"] = $new_ind["rate"];
            $words[$i]["gene_code"] = $new_ind["gene_code"];
            echo $new_ind["word"] . " - " . $new_ind["rate"] . "<br />\n";
        }

        echo "end of first generation <br />\n";
        echo "----------------------------<br />\n";
        array_unique($words);
        usort($words, "cmp");
        return $words;
    }

    private
    function kill($genes)
    {
        if (count($genes) > self::SURVIVORS)
        {
            $diff = count($genes) - self::SURVIVORS;
            for ($i = 0; $i < $diff; $i++)
            {
                array_pop($genes);
            }

            echo "killed $diff individuals<br />\n";
        }

        return $genes;
    }

    private
    function populate($genes)
    {
        $this->generations++;
        $new_genes = array();
        echo "------------ START OF " . $this->generations . " th GENERATION ----------------<br />\n";

// cross everything

        usort($this->best, "cmp");
        for ($j = 0; $j < self::OFFSPRING; $j++)
        {
            for ($i = 0; $i < count($genes); $i++)
            {
                $this->population++;
                $best = FALSE;
                if ($genes[$i]["rate"] >= $this->max_rate)
                {
                    $new_gene = $genes[$i]["gene_code"];
                    $best = TRUE;
                    echo "stay still<br />";
                }
                else
                {
                    if ($genes[$i]["gene_code"] !== $genes[$i + 1]["gene_code"] && $genes[$i + 1]["gene_code"])
                    {
                        $new_gene = $this->cross($genes[$i]["gene_code"], $genes[$i + 1]["gene_code"]);
                    }
                    else
                        if ($genes[$i]["gene_code"] !== $genes[$i + 2]["gene_code"] && $genes[$i + 2]["gene_code"])
                        {
                            $new_gene = $this->cross($genes[$i]["gene_code"], $genes[$i + 2]["gene_code"]);
                        }
                        else
                        {
                            $new_gene = $genes[$i]["gene_code"];
                            echo "crossed nothing!<br />\n";
                        }
                }

                if ($new_gene == $genes[$i]["gene_code"] && $i !== 0)
                {
                    $new_gene = $this->mutate($new_gene);
                    echo "<span style='color:red'>mutated unexpectedly!</span><br />\n";
                }

                $mutation = FALSE;
                if ($this->population % self::MUTATION_SIZE == 0)
                {

// mutate the new created gene if the mutation size reached

                    echo "mutation<br />\n";
                    $mutation = TRUE;
                }

                $new_ind = $this->generate($new_gene, $mutation);
                $new_genes[$i]["word"] = $new_ind["word"];
                $new_genes[$i]["rate"] = $new_ind["rate"];
                $new_genes[$i]["gene_code"] = $new_ind["gene_code"];
                if ($new_ind["rate"] >= $this->max_rate && !in_array($new_ind, $this->best))
                {
                    $this->max_rate = $new_ind["rate"];
                    $this->best[] = $new_ind;
                    usort($this->best, "cmp");
                }

                echo $new_ind["word"] . " - " . $new_ind["rate"] . "<br />\n";
            }
        }

        array_unique($new_genes);
        usort($new_genes, "cmp");
        return $new_genes;
    }

    private
    function cross($gene1, $gene2)
    {
        $new_gene = array();
        for ($i = 0; $i < $this->lenght; $i++)
        {
            $rand = rand(0, 1);
            if ($rand)
            {
                $new_gene[$i] = $gene1[$i];
            }
            else
            {
                $new_gene[$i] = $gene2[$i];
            }
        }

        if ($new_gene !== $gene1 && $new_gene !== $gene2)
        {
            echo "<span style='color:green'>cross successfull </span><br />";
        }

        return $new_gene;
    }

    public

    function execute()
    {

// the first gene code

        $gene_code = $this->generate_gene_code();
        $first_population = $this->first_population($gene_code);
        $result = $this->evolution($first_population);
        return $result;
    }

    private
    function evolution($genes_data)
    {

// return $genes_data;

        if ($genes_data[0]["rate"] >= self::SUCCESS_RATE)
        {
            echo $genes_data[0]["word"] . " found after " . $this->population . " try";
            return $genes_data;
        }
        else
        {
            if (count($this->best) > self::SURVIVORS || $this->passed)
            {
                usort($this->best, "cmp");
                $this->best = $this->kill($this->best);
                $genes = $this->best;
                $this->passed = TRUE;
            }
            else
            {
                $genes = $genes_data;
            }

            return $this->evolution($this->populate($genes));
        }
    }

    private
    function generate_gene_code()
    {
        $code = array();
        for ($i = 0; $i < $this->lenght; $i++)
        {
            $code[] = rand(0, strlen(self::LETTERS));
        }

        return $code;
    }


}
