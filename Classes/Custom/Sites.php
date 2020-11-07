<?php

namespace \Aimeos\Aimeos\Custom;


class Sites
{
	public function tca() : array
	{
		$sql = 'SELECT "siteid", "label", "level" FROM "mshop_locale_site" ORDER BY "nleft"';
		$dbm = \Aimeos\Aimeos\Base::getContext( \Aimeos\Aimeos\Base::getConfig() )->db();

		$conn = $dbm->acquire( 'db-locale' );
		$result = $conn->create( $sql )->execute();

		$list = [['', '']];

		while( $row = $result->fetch() ) {
			$list[] = [$row['label'], $row['siteid']];
		}

		$dbm->release( $conn, 'db-locale' );

		return $list;
	}
}