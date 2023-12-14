<?php

namespace App\Http\Masters\Gen\Repositories\RepoInterface;
use GuzzleHttp\Psr7\Request;

use App\Http\Masters\Gen\Requests\LanguageRequest;

interface LanguageRepoInterface
{
    public function add(mixed $fileUrls,LanguageRequest $data);

    public function find(Request $request, int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(LanguageRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
