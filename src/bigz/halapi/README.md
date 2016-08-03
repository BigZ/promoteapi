/artists?limit=2&page=2&sorting[id]=asc&filtervalue[name]=%27punk%27

``
public function getArtistAction(Artist $artist)
    {
        $relationFactory = new RelationFactory(
            $this->get('router'),
            $this->get('annotation_reader'),
            $this->get('doctrine.orm.entity_manager')
        );
        $halApiBuilder = new HALAPIBuilder($relationFactory);
        $serializer = $halApiBuilder->getSerializer();

        return new Response($serializer->serialize($artist, 'json'));
    }
````

```
public function getArtistsAction(ParamFetcher $paramFetcher)
    {
        $relationFactory = new RelationFactory(
            $this->get('router'),
            $this->get('annotation_reader'),
            $this->get('doctrine.orm.entity_manager')
        );
        $halApiBuilder = new HALAPIBuilder($relationFactory);
        $serializer = $halApiBuilder->getSerializer();

        $representation = new PaginationFactory(
            $this->get('router'), $this->get('doctrine.orm.entity_manager')
        );

        return new Response($serializer->serialize(
            $representation->getRepresentation(Artist::class, $paramFetcher),
            'json'
        ));
    }
```