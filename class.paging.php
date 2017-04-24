<?php
/************************************************
*	========================================	*
*	Perfect MySQL Paging						*
*	========================================	*
*	Script Name: class.paging.php				*
*	Developed By: Muhammad Jawad Arshad			*
*	Email: muhammadjawad17@yahoo.com			*
*	Date Created: 08-JULY-2009					*
*	Last Modified: 08-JULY-2009					*
************************************************/
?>
<?php
class PAGING
{
	var $sql,$records,$pages;
	/*
	Variables that are passed via constructor parameters
	*/
	var $page_no,$total,$limit,$first,$previous,$next,$last,$start,$end;
	/*
	Variables that will be computed inside constructor
	*/
	function PAGING($sql,$records=15,$pages=7)
	{
		if($pages%2==0)
			$pages++;
		/*
		The pages should be odd not even
		*/
		$res=mysql_query($sql) or die($sql." - ".mysql_error());
		$total=mysql_num_rows($res);
		$page_no=isset($_GET["page_no"])?$_GET["page_no"]:1;
		/*
		Checking the current page
		If there is no current page then the default is 1
		*/
		$limit=($page_no-1)*$records;
		$sql.=" limit $limit,$records";
		/*
		The starting limit of the query
		*/
		$first=1;
		$previous=$page_no>1?$page_no-1:1;
		$next=$page_no+1;
		$last=ceil($total/$records);
		if($next>$last)
			$next=$last;
		/*
		The first, previous, next and last page numbers have been calculated
		*/
		$start=$page_no;
		$end=$start+$pages-1;
		if($end>$last)
			$end=$last;
		/*
		The starting and ending page numbers for the paging
		*/
		if(($end-$start+1)<$pages)
		{
			$start-=$pages-($end-$start+1);
			if($start<1)
				$start=1;
		}
		if(($end-$start+1)==$pages)
		{
			$start=$page_no-floor($pages/2);
			$end=$page_no+floor($pages/2);
			while($start<$first)
			{
				$start++;
				$end++;
			}
			while($end>$last)
			{
				$start--;
				$end--;
			}
		}
		/*
		The above two IF statements are kinda optional
		These IF statements bring the current page in center
		*/
		$this->sql=$sql;
		$this->records=$records;
		$this->pages=$pages;
		$this->page_no=$page_no;
		$this->total=$total;
		$this->limit=$limit;
		$this->first=$first;
		$this->previous=$previous;
		$this->next=$next;
		$this->last=$last;
		$this->start=$start;
		$this->end=$end;
	}
	function show_paging($url,$params="")
	{
		$paging="";
		if($this->total>$this->records)
		{
			$page_no=$this->page_no;
			$first=$this->first;
			$previous=$this->previous;
			$next=$this->next;
			$last=$this->last;
			$start=$this->start;
			$end=$this->end;
			if($params=="")
				$params="?page_no=";
			else
				$params="?$params&page_no=";
			$paging.="<ul class='paging'>";
			$paging.="<li class='paging-current'>Page $page_no of $last</li>";
			if($page_no==$first)
				$paging.="<li class='paging-first paging-disabled'><a href='javascript:void(0)'>&lt;&lt;</a></li>";
			else
				$paging.="<li class='paging-first'><a href='$url$params$first'>&lt;&lt;</a></li>";
			if($page_no==$previous)
				$paging.="<li class='paging-previous paging-disabled'><a href='javascript:void(0)'>&lt;</a></li>";
			else
				$paging.="<li class='paging-previous'><a href='$url$params$previous'>&lt;</a></li>";
			for($p=$start;$p<=$end;$p++)
			{
				$paging.="<li";
				if($page_no==$p)
					$paging.=" class='paging-active'";
				$paging.="><a href='$url$params$p'>$p</a></li>";
			}
			if($page_no==$next)
				$paging.="<li class='paging-next paging-disabled'><a href='javascript:void(0)'>&gt;</a></li>";
			else
				$paging.="<li class='paging-next'><a href='$url$params$next'>&gt;</a></li>";
			if($page_no==$last)
				$paging.="<li class='paging-last paging-disabled'><a href='javascript:void(0)'>&gt;&gt;</a></li>";
			else
				$paging.="<li class='paging-last'><a href='$url$params$last'>&gt;&gt;</a></li>";
			$paging.="</ul>";
		}
		return $paging;
	}
}
?>